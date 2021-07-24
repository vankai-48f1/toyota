<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Blocker\FirstInteraction;

/**
 * Provide Import and Export of the settings of the plugin
 */
class UltimateReorder extends Base
{
    public $adminPriority = -1;
    public $priority = 99;
    public $tab = 'ultimate';
    public $title = 'Maximum available speed';
    public $description = ""; //"Delays script loading to 2 seconds";
    public $disabledInUltimateMode = false;
    public $defaultEnabled = false;

    public $pattern = [['.*', '']];

    public function backend_display_settings()
    {
        echo '<div id="' . $this->id . '" class="ultimate"
                    data-prefix="' . $this->id . '" 
                    data-title="' . $this->title . '"></div>';
    }

    public function backend_save_settings($sanitized, $settings)
    {
        // $exists = isset($sanitized[$this->id]['enabled']);
        $sanitized[$this->id] = array_merge($settings[$this->id], $sanitized[$this->id] ?: []);
        $sanitized[$this->id]['enabled'] = true;
        return $sanitized;
    }

    /* triggered from wpmeteor_load_settings */
    public function load_settings($settings)
    {
        $settings[$this->id] = isset($settings[$this->id])
            ? $settings[$this->id]
            : ['enabled' => true];

        $settings[$this->id]['id'] = $this->id;
        $settings[$this->id]['delay'] = @$settings[$this->id]['delay'] ?: 1;
        $settings[$this->id]['after'] = 'REORDER';
        $settings[$this->id]['description'] = $this->description;
        return $settings;
    }

    public function frontend_rewrite($buffer, $settings)
    {
        // Fast Velocity Minify Delay JS compatibility
        /*
        if (is_plugin_active('fast-velocity-minify/fvm.php')) {
            $buffer = preg_replace('/\s+type=([\'"])fvm-script-delay\1/i', ' type=\'text/javascript\'', $buffer);
        }
        */

        /*
        if (is_plugin_active('wp-rocket/wp-rocket.php')) {
            $buffer = preg_replace('/\s+type=([\'"])rocketlazyloadscript\1\s+data-rocket-type=([\'"])text\/javascript\2/i', ' type=\'text/javascript\'', $buffer);
            // type="rocketlazyloadscript" data-rocket-type='text/javascript'
        }*/

        $REPLACEMENTS = [];
        $buffer = preg_replace_callback('/(<script\b[^>]*?>)(.*?)(<\/\s*script>)/is', function ($matches) use (&$REPLACEMENTS) {
            list($everything, $tag, $content, $closingTag) = $matches;
            $hasSrc = preg_match('/\s+src=/i', $tag, $matches);
            $hasType = preg_match('/\s+type=/i', $tag);
            $isJavascript = !$hasType || preg_match('/\s+type=([\'"])text\/javascript\1/i', $tag);
            $noOptimize = preg_match('/data-wpmeteor-nooptimize="true"/i', $tag);
            if ($isJavascript && !$hasSrc && !$noOptimize) {
                // inline script
                if (apply_filters('wpmeteor_exclude', false, $content)) {
                    return preg_replace('/<script\b/', '<script data-wpmeteor-nooptimize="true"', $everything);
                }
                $result = $tag . "WPMETEOR[" . count($REPLACEMENTS) . "]WPMETEOR" . $closingTag;
                $REPLACEMENTS[] = $content;
                return $result;
            }
            return $everything;
        }, $buffer);

        $buffer = preg_replace_callback('/<script\b[^>]*?>/i', function ($matches) {
            list($tag) = $matches;

            $EXTRA = constant('WPMETEOR_EXTRA_ATTRS') ?: '';

            $result = $tag;
            if (!preg_match('/\s+data-src=/i', $result) 
                && !preg_match('/data-wpmeteor-nooptimize="true"/i', $result)
                && !preg_match('/data-rocketlazyloadscript=/i', $result)) {

                $src = preg_match('/\s+src=([\'"])(.*?)\1/i', $result, $matches)
                    ? $matches[2]
                    : null;
                $hasType = preg_match('/\s+type=/i', $result);
                $isJavascript = !$hasType || preg_match('/\s+type=([\'"])text\/javascript\1/i', $result);
                if ($isJavascript) {
                    if ($src) {
                        if (apply_filters('wpmeteor_exclude', false, $src)) {
                            return $result;
                        }
                        $result = preg_replace('/\s+src=/i', " data-src=", $result);
                    }
                    if ($hasType) {
                        $result = preg_replace('/\s+type=([\'"])text\/javascript\1/i', " type=\"javascript/blocked\"", $result);
                    } else {
                        $result = preg_replace('/<script/i', "<script type=\"javascript/blocked\"", $result);
                    }
                    $result = preg_replace('/<script/i', "<script ${EXTRA} data-wpmeteor-after=\"REORDER\"", $result);
                }
            }
            return preg_replace('/\s*data-wpmeteor-nooptimize="true"/i', '', $result);
        }, $buffer);

        $buffer = preg_replace_callback('/WPMETEOR\[(\d+)\]WPMETEOR/', function ($matches) use (&$REPLACEMENTS) {
            return $REPLACEMENTS[(int)$matches[1]];
        }, $buffer);

        // JetPack Likes workaround - lazyloading iframe so it don't window.postMessage early
        $buffer = preg_replace_callback('/<iframe\b[^>]*?>/i', function ($matches) {
            list($tag) = $matches;
            if (preg_match('/\s+src=(["\'])https?:\/\/widgets\.wp\.com/i', $tag)) {
                return preg_replace('/\s+src=/i', " " . constant('WPMETEOR_EXTRA_ATTRS') . " data-wpmeteor-after=\"REORDER\" data-src=", $tag);
            }
            return $tag;
        }, $buffer);
    
        return $buffer;
    }

    public function frontend_adjust_wpmeteor($wpmeteor, $settings)
    {
        if (!$settings[$this->id]['enabled']) {
            $wpmeteor['rdelay'] = 1000;
        } else {
            $wpmeteor['rdelay'] = (int) $settings[$this->id]['delay'] === 3
             ? 86400000 # one day
             : (int) $settings[$this->id]['delay'] * 1000;
        }
        return $wpmeteor;
    }
}
