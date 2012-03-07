jQuery(document).ready(function(jQuery){

  // Add listing to list.
  jQuery(".fls-add-listing").live("click", function(event) {
    event.preventDefault();

    // figure out which featured listings option we're changing
    var featured_section = jQuery(this).attr("name");

    // set address to add to featured list
    var added_listing = jQuery('select#fls-select-address option').filter(":selected").attr('name', featured_section);
    added_listing = added_listing.text().replace(/(<([^>]+)>)/ig,"");

    // remove spaces and punctuation
    var added_listing_class = added_listing.replace(/\b[-.,()&jQuery#!\[\]{}"']+\B|\B[-.,()&jQuery#!\[\]{}"']+\b/g, "").replace(/ /g,'');
    
    // add listing to 'added listings' list
    jQuery("ul#fls-added-listings >" + featured_section).append('<li id="' + added_listing_class + '">' + added_listing + ' &nbsp; &nbsp;<a href="#" class="delete delete-' + added_listing_class + '">Remove</a></li>');

      // add listing to 'added listings' list
      jQuery("#fls-added-listings").append('<li id="' + added_listing_class + '">' + added_listing + ' &nbsp; &nbsp;<a href="#" class="delete delete-' + added_listing_class + '">Remove</a></li>');

      jQuery(".hidden-featured-listings").append('<option value="' + added_listing_class + '">' + added_listing + '</option>');

      // 
      // jQuery.each($('#fls-added-listings li').serializeArray(), function(i, field) {
      //   aoData.push({"name" : field.name, "value" : field.value});
      //   form[field.name] = field.value;

      // });

  });

  // Remove listing from list.
  jQuery("#fls-added-listings li a.delete").live("click", function(event) {
    event.preventDefault();

    varr = jQuery(this).closest('li').attr('id');

    // Remove the li that holds this added_listing
    jQuery(this).closest('li').remove();

    //THIS DOESN'T QUITE WORK
    jQuery(".hidden-featured-listings option").attr("value", varr).remove();
  });

});