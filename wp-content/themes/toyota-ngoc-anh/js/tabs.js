var itemTabsContent = jQuery('.list-tab-content > section');
var tabNameActive = jQuery(".list-tab-name li a");

jQuery('.list-tab-content > section').css("display","none");

// console.log(tabNameActive);
itemTabsContent.css("display", "none");

tabNameActive.each((index, elemt) => {
    var elemtTarget = jQuery(elemt);
    var dataIdSection = jQuery(elemt).attr("data-id-section");


    itemTabsContent.each((index, elmt) => {
        var contentElement = jQuery(elmt);
        var contentId = contentElement.attr("id");

        if (elemtTarget.hasClass('active')) {
            dataIdSection = jQuery(elemt).attr("data-id-section");
            if (dataIdSection == contentId) {
                contentElement.css("display", "block");
            }
        }
    });

    // CLICK ACTIVE TABS
    elemtTarget.on('click', function (e) {
        e.preventDefault();

        jQuery(this).closest('.list-tab-name').find('li a').removeClass('active');
        jQuery(this).addClass('active');
        jQuery(this).closest('.service-tab-content').find('.list-tab-content > section').css('display', 'none');

        itemTabsContent.each((index, elmt) => {
            var contentElement = jQuery(elmt);
            var contentId = contentElement.attr("id");
    
            if (dataIdSection == contentId) {
                contentElement.css("display", "block");
            }
        });
    })
});

