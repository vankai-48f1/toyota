import jQueryMock from './includes/mocks/jquery';
import InteractionEvents from './includes/utils/interaction-events';
import dispatcher from './includes/utils/dispatcher';
import delta from './includes/utils/delta';
// import lazysizes from './includes/utils/lazysizes-core';
// import lazybg from './includes/utils/ls.bgset';
import elementorAnimations from './includes/elementor/animations';
import elementorPP from './includes/elementor/pp-menu';

const DCL = 'DOMContentLoaded';
const RSC = 'readystatechange';
const separator = "----";
const S = 'SCRIPT';
const I = 'requestIdleCallback';
const N = null;
const c = process.env.DEBUG ? console.log : () => { };
const ce = console.error;
// const lazyloaded = 'lazyloaded';
// const lazybeforeunveil = 'lazybeforeunveil';
const prefix = 'data-wpmeteor-';

(function (w, d, a, r, ga, sa, ra, L, E) {

    const windowEventPrefix = w.constructor.name + '::';
    const documentEventPrefix = d.constructor.name + '::';

    const forEach = function (callback, thisArg) {
        thisArg = thisArg || w;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    }

    if ('NodeList' in w && !NodeList.prototype.forEach) {
        process.env.DEBUG && c("polyfilling NodeList.forEach");
        NodeList.prototype.forEach = forEach;
    }
    if ('HTMLCollection' in w && !HTMLCollection.prototype.forEach) {
        process.env.DEBUG && c("polyfilling HTMLCollection.forEach");
        HTMLCollection.prototype.forEach = forEach;
    }

    if (_wpmeteor['elementor-animations']) {
        elementorAnimations();
    }

    if (_wpmeteor['elementor-pp']) {
        elementorPP();
    }

    const reorder = [];
    const iframes = [];

    let eventQueue = [];
    let listeners = {};
    let WindowLoaded = false;
    let firstInteractionFired = false;

    const nextTick = setTimeout;

    if (process.env.DEBUG) {
        d[a](RSC, () => {
            c(delta(), separator, RSC, d.readyState);
        });

        d[a](DCL, () => {
            c(delta(), separator, DCL);
        });

        dispatcher.on('l', () => {
            c(delta(), separator, "L");
        });

        w[a](L, () => {
            c(delta(), separator, L);
        });
    }

    let origAddEventListener, origRemoveEventListener;

    // saving original methods
    let dOrigAddEventListener = d[a].bind(d);
    let dOrigRemoveEventListener = d[r].bind(d);
    let wOrigAddEventListener = w[a].bind(w);
    let wOrigRemoveEventListener = w[r].bind(w);

    if ("undefined" != typeof EventTarget) {
        origAddEventListener = EventTarget.prototype.addEventListener;
        origRemoveEventListener = EventTarget.prototype.removeEventListener;
        // saving original methods
        dOrigAddEventListener = origAddEventListener.bind(d);
        dOrigRemoveEventListener = origRemoveEventListener.bind(d);
        wOrigAddEventListener = origAddEventListener.bind(w);
        wOrigRemoveEventListener = origRemoveEventListener.bind(w);
    }
    const dOrigCreateElement = d.createElement.bind(d);
    const readyStateGetter = d.__proto__.__lookupGetter__('readyState').bind(d);

    let readyState;
    Object.defineProperty(d, 'readyState', {
        get() { return readyState ? readyState : readyStateGetter() },
        set(value) { return readyState = value },
    });

    const hasListeners = (eventNames) => {
        return eventNames.filter(k => [windowEventPrefix, documentEventPrefix].filter(p => (listeners[p + k] || []).length).length).length;
    }

    const firedListeners = {}
    const fireQueuedEvents = () => {
        const toDelete = []
        eventQueue.forEach(([event, readyState, context]) => {
            if (!context) {
                context = event.target;
            }
            try {
                const name = context.constructor.name + '::' + event.type;
                if ((listeners[name] || []).length) {
                    d.readyState = readyState;
                    // listeners[name].forEach doesn't work as the listeners might be added 
                    // during the loop
                    for (let i = 0; i < listeners[name].length; i++) {
                        const func = listeners[name][i];
                        // readystatechanges fires multiple time times on same 
                        // listener with different readyState, accounting for that
                        const listenerKey = name + '::' + i + '::' + readyState;
                        if (!firedListeners[listenerKey]) {
                            firedListeners[listenerKey] = true;
                            try {
                                process.env.DEBUG && c(delta(), 'firing ' + event.type + '(' + d.readyState + ') for', func.prototype ? func.prototype.constructor : func);
                                if (!func.hasOwnProperty('prototype') || func.prototype.constructor === func) {
                                    func.bind(context)(event);
                                } else {
                                    func(event);
                                }
                            } catch (e) {
                                ce(e, func);
                            }
                        }
                    };
                    toDelete.push(name);
                    d.readyState = readyStateGetter();
                }
            } catch (e) {
                ce(e);
            }
        });
        toDelete.forEach(name => { delete listeners[name] });
    }

    dOrigAddEventListener(DCL, (e) => {
        process.env.DEBUG && c(delta(), "enqueued document " + DCL);
        eventQueue.push([e, d.readyState, d])
    });
    dOrigAddEventListener(RSC, (e) => {
        process.env.DEBUG && c(delta(), "enqueued document " + RSC);
        eventQueue.push([e, d.readyState, d])
    });
    wOrigAddEventListener(DCL, (e) => {
        process.env.DEBUG && c(delta(), "enqueued window " + DCL);
        eventQueue.push([e, d.readyState, w])
    });
    wOrigAddEventListener(L, (e) => {
        process.env.DEBUG && c(delta(), "enqueued window " + L);
        eventQueue.push([e, d.readyState, w])
    });

    // there are two cases
    // 1. fi fires before window.load as a resut of user interaction
    // 2. window.load fires before fi - 3rd party scripts might trigger it manually
    dispatcher.on('fi', () => {
        process.env.DEBUG && c(delta(), separator, "starting iterating on first interaction");
        firstInteractionFired = true;
        iterating = true;
        nextTick(iterate); // starts the iteration
    });

    const startIterating = () => {
        WindowLoaded = true;
        // this might trigger L twice if iteration cycle hasn't finished yet
        // and it fires inside a cycle causing various side effects
        // double checking on that
        if (firstInteractionFired && !iterating) {
            process.env.DEBUG && c(delta(), separator, "starting iterating on window.load");
            nextTick(iterate);
        }
        wOrigRemoveEventListener(L, startIterating);
    }
    wOrigAddEventListener(L, startIterating);

    // currently InteractionEvents listens for imagesloaded ('i') event, but if I get rid of it
    // then the order will matter - it is important to install InteractionEvents after window.load tracking setup
    (new InteractionEvents()).init(_wpmeteor.rdelay);

    let i = 0;
    let iterating = false;
    const iterate = () => {
        process.env.DEBUG && c(delta(), 'it', i++, reorder.length);
        const element = reorder.shift();
        if (element) {
            // process.env.DEBUG && c(separator, "iterating", element, element.dataset);
            if (element[ga]('data-src')) {
                // process.env.DEBUG && c(delta(), "src", element);
                if (element.hasAttribute('async')) {
                    // process.env.DEBUG && c(delta(), "src", element);
                    unblock(element);
                    nextTick(iterate);
                } else {
                    unblock(element, iterate);
                }
            } else if (element.type == 'javascript/blocked') {
                unblock(element);
                // allow inserted script to execute
                nextTick(iterate);
            } else if (element.hasAttribute(prefix + "onload")) {
                const script = element[ga](prefix + "onload");
                try {
                    (new Function(script)).call(element);
                } catch (e) {
                    ce(e);
                }
                nextTick(iterate);
                // not used
                // } else if (element.loaded === 0) {
                //     process.env.DEBUG && c(delta(), 'still waiting for', element.src)
                //     element[a](L, iterate); // L = load
                //     element[a](E, iterate); // E = error
            } else {
                // it might be wrongfully processed script by backend, eg type="application/ld+json" 
                // and execution will stop here
                // process.env.DEBUG && c("running next iteration");
                nextTick(iterate);
            }
        } else {
            // process.env.DEBUG && c('loaded all the scripts');
            // not restoring original addEventListener
            // to avoid unexpected failures, 
            // however, that triggers spurious handlers which were sleeping
            // d[a] = dOrigAddEventListener;
            if (hasListeners([DCL, RSC])) {
                fireQueuedEvents();
                nextTick(iterate);
            } else if (firstInteractionFired && WindowLoaded) {
                // techinally, firstInteractionFired should already be true
                // as cycle starts in 'fi' listener
                if (hasListeners([L])) {
                    fireQueuedEvents();
                    nextTick(iterate);
                } else {
                    // CloudFlare RocketLoader workaround
                    if (w.RocketLazyLoadScripts) {
                        try {
                            RocketLazyLoadScripts.run();
                        } catch(e) {
                            ce(e);
                        }
                    }
                    // process.env.DEBUG && c('running emulatedWindowLoaded');
                    // technically, iterating = false is not needed
                    // as the only place where it is checked is inside window.load
                    // and here he has already fired as WindowLoaded === true
                    // iterating = false; 
                    setTimeout(() => dispatcher.emit('l'));
                }
            } else {
                // exiting iterate() cycle
                iterating = false;
            }
        }
    };

    const cloneScript = (el) => {
        const newElement = d.createElement(S);
        const attrs = el.attributes;

        // move attributes
        for (var i = attrs.length - 1; i >= 0; i--) {
            newElement[sa](attrs[i].name, attrs[i].value);
        }
        newElement.bypass = true;
        newElement.type = 'text/javascript';

        // CloudFlare RocketLoader workaround
        if ((el.text || "").match(/^\s*class RocketLazyLoadScripts/)) {
            newElement.text = el.text.replace(/^\s*class RocketLazyLoadScripts/, 'window.RocketLazyLoadScripts=class').replace('RocketLazyLoadScripts.run();','');
        } else {
            newElement.text = el.text
        }

        newElement[ra]('data-wpmeteor-after');
        return newElement;
    }

    const replaceScript = (el, newElement) => {
        const parentNode = el.parentNode;
        if (parentNode)
            parentNode.replaceChild(newElement, el);
    }

    const unblock = (el, callback) => {
        // const ds = el.dataset;
        if (el[ga]('data-src')) {
            process.env.DEBUG && c(delta(), "unblocked src", el[ga]('data-src'));
            const newElement = cloneScript(el);

            const addEventListener = origAddEventListener
                ? origAddEventListener.bind(newElement)
                : newElement[a].bind(newElement);

            if (callback) {
                const f = () => nextTick(callback);
                addEventListener(L, f);
                addEventListener(E, f);
            }

            // addEventListener(E, e => ce(e)); // E = error
            newElement.src = el[ga]('data-src');
            newElement[ra]('data-src');

            replaceScript(el, newElement);

            // el.bypass = true;
            // if (onLoad)
            //     el[a](L, () => nextTick(onLoad)); // L = load
            // if (onError)
            //     el[a](E, () => nextTick(onError)); // E = error
            // el[a](E, e => ce(e)); // E = error
            // el.src = el[ga]('data-src');
            // el[ra]('data-src');
        } else if (el.type === 'javascript/blocked') {
            // onLoad is never passed here
            process.env.DEBUG && c(delta(), "unblocked inline", el);
            replaceScript(el, cloneScript(el));
        } else {
            process.env.DEBUG && c(delta(), "already unblocked", el.src);
            if (onLoad) {
                onLoad();
            }
        }
    }

    // Capturing and queueing DOMContentLoaded event handlers
    d[a] = (event, func, ...args) => {
        if (func && (event === DCL || event === RSC)) {
            const name = documentEventPrefix + event;
            listeners[name] = listeners[name] || [];
            listeners[name].push(func);
            return;
        }
        return dOrigAddEventListener(event, func, ...args);
    }

    d[r] = (event, func) => {
        if (event === DCL) {
            const name = documentEventPrefix + event;
            listeners[name] = (listeners[name] || []).filter(f => f !== func);
        }
        return dOrigRemoveEventListener(event, func);
    };

    dispatcher.on('pre', () => reorder.forEach(script => {
        const src = script[ga]('data-src');
        if (src) {
            var s = dOrigCreateElement('link');
            s.rel = 'pre' + L;
            s.as = 'script';
            s.href = src;
            s.crossorigin = true;
            d.head.appendChild(s);
            process.env.DEBUG && c(delta(), 'preloading', src);
        }
    }));

    dOrigAddEventListener(DCL, () => {
        d.querySelectorAll('script[' + prefix + 'after]').forEach(el => reorder.push(el));
        // we will loose all event listeners, so we'd better track addEventListener/removeEventListener as well
        const querySelectors = ['link'].map(n => n + '[' + prefix + 'onload]').join(',');
        d.querySelectorAll(querySelectors).forEach(el => reorder.push(el));
        d.querySelectorAll('iframe[' + prefix + 'after]').forEach(el => iframes.push(el));
    });

    (new jQueryMock()).init();
    /* jQuery.ready fired */
    dispatcher.on('l', () => {
        iframes.forEach(iframe => {
            process.env.DEBUG && c(delta(), "loading iframe", iframe);
            iframe.src = iframe[ga]('data-src');
        });
    })

    /*
    if (location.href.match(/wpmeteordisable/)) {
        dOrigAddEventListener(DCL, () => {
            preload();
            iterate();
        });
        return;
    }

    d.createElement = function (...args) {
        // If this is not a script tag, bypass
        // dont rely on window loaded or document loaded as the tags might 
        // be inserted long after this
        if (args[0].toUpperCase() !== S) {

            // Binding to document is essential
            return dOrigCreateElement(...args)
        }

        const scriptElt = dOrigCreateElement(...args);
        // scriptElt.blackListed = false;

        // Backup the original setAttribute function
        const originalSetAttribute = scriptElt[sa].bind(scriptElt)
        const originalGetter = scriptElt.__proto__.__lookupGetter__('src').bind(scriptElt);

        Object.defineProperties(scriptElt, {
            'src': {
                get: originalGetter,
                set(value) {
                    if (scriptElt.bypass) {
                        // process.env.DEBUG && c('bypass for', value.toString());
                        return originalSetAttribute('src', value);
                    }
                    originalSetAttribute(prefix + 'after', 'REORDER');
                    return scriptElt.dataset.src = value;
                }
            }
        })

        // Monkey patch the setAttribute function so that the setter is called instead.
        // Otherwise, setAttribute('type', 'whatever') will bypass our custom descriptors!
        scriptElt[sa] = function (name, value) {
            if (name === 'src')
                scriptElt[name] = value
            else
                HTMLScriptElement.prototype[sa].call(scriptElt, name, value)
        }

        return scriptElt
    }

    // have to find scripts before us
    const observer = new MutationObserver(mutations => {
        mutations.forEach(({ addedNodes }) => {
            addedNodes.forEach(node => {
                // For each added script tag
                if (node.nodeType === 1) {
                    if (S === node.tagName && !node.bypass) {
                        const ds = node.dataset;
                        // const hasReorder = ds.wpmeteorAfter === 'REORDER';
                        // process.env.DEBUG && c([ds.src, node.src, ds.src && !node.src, node.dataset]);
                        // if (ds.src && !node.src || hasReorder) {
                        if (ds.wpmeteorAfter === 'REORDER') {
                            // process.env.DEBUG && c('pushing', node)
                            process.env.DEBUG && c(delta(), "blocked", node.tagName, ds.src || node);
                            reorder.push(node);
                            if (!iterating) {
                                // we have to restart iterate() to insert missing scripts
                                process.env.DEBUG && c(delta(), 'restarting reordering');
                                iterating = true;
                                nextTick(iterate);
                            }
                        } else if (node.src || node[ga]("src")) {
                            process.env.DEBUG && c(delta(), "detected", node[ga]("src"));
                            node.loaded = 0;
                            node[a](L, () => node.loaded = 1); // L = loaded
                            node[a](E, () => node.loaded = 1); // E = error
                            reorder.push(node);
                        } else {
                            // ce('missed', node);
                        }
                    } else if ('IFRAME' == node.tagName && node.dataset.wpmeteorAfter) {
                        iframes.push(node);
                    }
                }
            })
        })
    });
    */

    /* we have to override document.write as all of them will fire after DOMContentLoaded */
    let documentWrite = (str) => {
        if (d.currentScript) { // that implicitely means DOMContentLoad already fired
            d.currentScript.insertAdjacentHTML('afterend', str);
        } else {
            ce(delta(), "document.currentScript not set", str);
        }
    };
    Object.defineProperty(d, 'write', {
        get() { return documentWrite },
        set(func) { return documentWrite = func },
        // writable: false,
        // configurable: false,
    });

    // Capturing and queueing Window Load event handlers
    w[a] = (event, func, ...args) => {
        if (func && (event === L || event === DCL)) { // L = load
            const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
            listeners[name] = listeners[name] || [];
            listeners[name].push(func);
            return;
        }
        // process.env.DEBUG && c(event, func);
        return wOrigAddEventListener(event, func, ...args);
    }

    w[r] = (event, func) => {
        if (event === L) { // L = load
            const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
            listeners[name] = (listeners[name] || []).filter(f => f !== func);
        }
        return wOrigRemoveEventListener(event, func);
    };

    // overriding window.onload and document.body.onload
    (() => {
        let handler;
        const name = windowEventPrefix + 'load';

        listeners[name] = listeners[name] || [];
        listeners[name].push(() => {
            process.env.DEBUG && c(delta(), separator, 'running window.onload', handler);
            if (handler) {
                handler();
            }
        })
        const options = {
            get() {
                process.env.DEBUG && c(delta(), separator, 'getting window.onload');
                return handler;
            },
            set(func) {
                process.env.DEBUG && c(delta(), separator, 'setting window.onload', func);
                return handler = func;
            },
            // rocket-loader from CloudFlare tries to override onload so we will let him
            // configurable: true,
        };
        Object.defineProperty(w, 'onload', options);
        dOrigAddEventListener(DCL, () => {
            // window.onload === document.body.onload
            Object.defineProperty(d.body, 'onload', options);
        });
    })();

    /*
    dispatcher.on('l', () => {
        observer.disconnect();
        d.createElement = dOrigCreateElement;
    });
    */

    let imagesToLoad = 1;
    let imagesLoadedCount = -1;
    const imageLoadedHandler = () => {
        imagesLoadedCount++;
        if (!--imagesToLoad) {
            process.env.DEBUG && c(delta(), "loaded " + imagesLoadedCount + " images");
            dispatcher.emit('i');
        }
    }

    const isCloseToViewport = (el) => {
        const rect = el.getBoundingClientRect();
        const wheight = (window.innerHeight || document.documentElement.clientHeight);
        const wwidth = (window.innerWidth || document.documentElement.clientWidth);
        const ratio = 1; // 0.5 = extra half of viewport, 1 extra viewport
        return (
            rect.top >= -1 * wheight * ratio
            && rect.left >= -1 * wwidth * ratio
            && rect.bottom <= wheight * (1 + ratio)
            && rect.right <= wwidth * (1 + ratio)
        );
    }

    const registerImages = () => {
        nextTick(() => {
            d.querySelectorAll('img').forEach(img => {
                if (!img.complete
                    && (img.currentSrc || img.src)
                    && (!(img.loading || '').toLowerCase() == 'lazy') || isCloseToViewport(img)) {
                    // img.complete might change during the following block running time
                    // so we can't rely on img.onload
                    const image = new Image();
                    image[a](L, imageLoadedHandler);
                    image[a](E, imageLoadedHandler);
                    image.src = img.currentSrc || img.src;
                    imagesToLoad++;
                }
            });
            imageLoadedHandler();
        });
        wOrigRemoveEventListener(L, registerImages);
    }
    wOrigAddEventListener(L, registerImages)

    // Starts the monitoring
    /*
    observer.observe(d.documentElement, {
        attributes: true,
        childList: true,
        subtree: true
    });
    */

    const origObjectDefineProperty = Object.defineProperty;
    Object.defineProperty = (object, property, options) => {
        if (object === w && (['jQuery', 'onload'].indexOf(property) >= 0)
            || (object === d || object === d.body) && ['readyState', 'write'].indexOf(property) >= 0) {
            process.env.DEBUG && ce('Denied ' + (object.constructor || {}).name + ' ' + property + ' redefinition');
            return object;
        }
        return origObjectDefineProperty(object, property, options);
    }

    Object.defineProperties = (object, properties) => {
        for (const i in properties) {
            Object.defineProperty(object, i, properties[i]);
        }
        return object;
    };
})(window,
    document,
    'addEventListener',
    'removeEventListener',
    'getAttribute',
    'setAttribute',
    'removeAttribute',
    'load',
    'error');
