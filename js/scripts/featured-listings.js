$(document).ready(function($){

  // Add listing to list.
  $("#add-listing-1").live("click", function(event) {
    event.preventDefault();
    if ($("select#fls-select-address-1 option").filter(":selected")) {
      // get the listing address
      var added_listing = $("select#fls-select-address-1 option").filter(":selected").text();
      // remove spaces and punctuation
      var added_listing_class = added_listing.replace(/\b[-.,()&$#!\[\]{}"']+\B|\B[-.,()&$#!\[\]{}"']+\b/g, "").replace(/ /g,'');
      // add listing to 'added listings' list
      $("#fls-added-listings-1").append('<li>' + added_listing + ' &nbsp; &nbsp;<a href="#" class="delete delete-' + added_listing_class + '">Remove</a></li>');
    }
  });

  // Remove listing from list.
  $("#fls-added-listings-1 li a.delete").live("click", function(event) {
    event.preventDefault();
    $(this).closest('li').remove();
  });

});


