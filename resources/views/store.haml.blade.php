@extends('app')

@section('content')
.section.full
  %h1 90+ Protein
.section.rating
  .bar
    .progress
      .progress-bar{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
  .display
    Average Rating
    .percentage
      92%
.section
  .product-detail
    .image
      %img{:src => "/images/products/sample.png"}/
    .selectors
      %p
        %select.form-control
          %option Select Size
        %p
          %select.form-control
            %option Select Flavour
          %p
            Servings:
            %b 12oz and up
            %p
              Price:
              %b £5 and up
  .cart
    %button Add to Cart
.section.related-products
  Related products
.section.reviews
  #accordion.panel-group{"aria-multiselectable" => "true", :role => "tablist"}
    .panel.panel-default
      %a{"aria-controls" => "collapseOne", "aria-expanded" => "true", "data-parent" => "#accordion", "data-toggle" => "collapse", :href => "#collapseOne", :role => "button"}
        #headingOne.panel-heading{:role => "tab"}
          %h4.panel-title
            Taste and Flavours
      #collapseOne.panel-collapse.collapse{"aria-labelledby" => "headingOne", :role => "tabpanel"}
        .panel-body
          #carousel-example-generic.carousel.slide{'data-interval'=>false}
            .carousel-inner{:role => "listbox"}
              .item.active
                .flavor
                  .title
                    Strawberry & Vanilla
                  .icon
                    %img{"src"=>'/images/icons/flavors/strawberry.png'}
                  .score
                    72/100
                  .progress
                    .progress-bar{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
                  .member-count
                    Taste rating by 5 OM members
                  .logos
                    %img{"src"=>'/images/logos/om.png'}
                    %img{"src"=>'/images/logos/nutrisport.png'}
              .item
                .flavor
                  .title
                    Strawberry & Vanilla
                  .icon
                    %img{"src"=>'/images/icons/flavors/strawberry.png'}
                  .score
                    72/100
                  .progress
                    .progress-bar{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
                  .member-count
                    Taste rating by 5 OM members
                  .logos
                    %img{"src"=>'/images/logos/om.png'}
                    %img{"src"=>'/images/logos/nutrisport.png'}
              .item
                .flavor
                  .title
                    Strawberry & Vanilla
                  .icon
                    %img{"src"=>'/images/icons/flavors/strawberry.png'}
                  .score
                    72/100
                  .progress
                    .progress-bar{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
                  .member-count
                    Taste rating by 5 OM members
                  .logos
                    %img{"src"=>'/images/logos/om.png'}
                    %img{"src"=>'/images/logos/nutrisport.png'}
              .item
                .flavor
                  .title
                    Strawberry & Vanilla
                  .icon
                    %img{"src"=>'/images/icons/flavors/strawberry.png'}
                  .score
                    72/100
                  .progress
                    .progress-bar{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
                  .member-count
                    Taste rating by 5 OM members
                  .logos
                    %img{"src"=>'/images/logos/om.png'}
                    %img{"src"=>'/images/logos/nutrisport.png'}

            / Controls
            %a.left.carousel-control{"data-slide" => "prev", :href => "#carousel-example-generic", :role => "button"}
              %span.glyphicon.glyphicon-chevron-left{"aria-hidden" => "true"}
              %span.sr-only Previous
            %a.right.carousel-control{"data-slide" => "next", :href => "#carousel-example-generic", :role => "button"}
              %span.glyphicon.glyphicon-chevron-right{"aria-hidden" => "true"}
              %span.sr-only Next        
          :javascript
            $(function() {
              $('#carousel-example-generic').carousel('pause');
            });
    .panel.panel-default
      %a.collapsed{"aria-controls" => "collapseTwo", "aria-expanded" => "false", "data-parent" => "#accordion", "data-toggle" => "collapse", :href => "#collapseTwo", :role => "button"}
        #headingTwo.panel-heading{:role => "tab"}
          %h4.panel-title
            Is 90+ Protein Good Value
      #collapseTwo.panel-collapse.collapse{"aria-labelledby" => "headingTwo", :role => "tabpanel"}
        .panel-body
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
    .panel.panel-default
      %a.collapsed{"aria-controls" => "collapseThree", "aria-expanded" => "false", "data-parent" => "#accordion", "data-toggle" => "collapse", :href => "#collapseThree", :role => "button"}
        #headingThree.panel-heading{:role => "tab"}
          %h4.panel-title
            How Does 90+ Protein Work
      #collapseThree.panel-collapse.collapse{"aria-labelledby" => "headingThree", :role => "tabpanel"}
        .panel-body
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.  
    .panel.panel-default
      %a.collapsed{"aria-controls" => "collapseThree", "aria-expanded" => "false", "data-parent" => "#accordion", "data-toggle" => "collapse", :href => "#collapseFour", :role => "button"}
        #headingFour.panel-heading{:role => "tab"}
          %h4.panel-title
            90+ Protein Reviews
      #collapseFour.panel-collapse.collapse.in{"aria-labelledby" => "headingFour", :role => "tabpanel"}
        .panel-body
          .banner
            Post your review below and win £100, £50, or £10 of store credit. 1 in 25 chance of winning.
          .overall-rating
            .clearfix
              .pull-left
                Overall Rating
              .pull-right
                92%
            .progress
              .progress-bar{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
          .taste-ratings
            .clearfix
              - for($i=0;$i<7;$i++)
                .taste-rating
                  .clearfix
                    .pull-left
                      Bananna
                    .pull-right
                      92%
                  .progress
                    .progress-bar{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
          .reviews
            - for($i=0;$i<2;$i++)
              .review
                .header
                  .image
                    %img{:src => "/images/products/sample.png"}/
                  .title
                    90+ Protein
                  .profile
                    %img{:src => "/images/users/user.png"}/
                    .byline
                      By John Smith
                .rating
                  .title
                    Product Rating
                  .row
                    .col-xs-10
                      .progress
                        .progress-bar.om-progress-primary{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
                    .col-xs-2.percentage.text-left
                      92%
                .rating
                  .title.text-center
                    Taste Rating - Bananna
                  .row
                    .col-xs-10
                      .progress
                        .progress-bar.om-progress-secondary{"aria-valuemax" => "100", "aria-valuemin" => "0", "aria-valuenow" => "92", :role => "progressbar", :style => "width: 92%;"}
                    .col-xs-2.percentage.text-left
                      92%
                .more.text-center
                  %button.btn.btn-xs.btn-primary{"aria-controls" => "collapse_{{$i}}", "aria-expanded" => "false", "data-target" => "#collapse_{{$i}}", "data-toggle" => "collapse", :type => "button"}
                    Read Full Review
                  .collapse.text-left{"id"=>"collapse_{{$i}}"}
                    This product is really good it gives you a great buzz and really helps you power through your workout. I totally recomend it and its definatley. One thing I will say is that that it didnt get 10/10 for me because it gave me a bad stomach I wont give details but was not totally pleasant.
                    .comment
                      Comments?
            .more.text-center
              %button.btn.btn-success.btn-xs
                Load More Reviews
          .user-review
            .title.row
              .col-xs-12.text-center
                Post Your Review
            .banner
              Post your review below and win £100, £50, or £10 of store credit. 1 in 25 chance of winning.
            .input
              .point.inline
                .question
                  What score do you give 90+ Protein?
                .answer
                  %input#overall_score{"data-slider-id" => "overall_score", "data-slider-max" => "100", "data-slider-min" => "0", "data-slider-step" => "1", "data-slider-value" => "14", :type => "text"}/
                  :javascript
                    jQuery('#overall_score').slider({
                    	formatter: function(value) {
                    		return 'Current value: ' + value;
                    	}
                    });  
              .point.inline
                .question
                  What Flavor did you have?
                .answer
                  %select.form-control
                    %option --order history--
              .point.inline
                .question
                  What score do you give that flavor?
                .answer
                  %input#flavor_score{"data-slider-id" => "flavor_score", "data-slider-max" => "100", "data-slider-min" => "0", "data-slider-step" => "1", "data-slider-value" => "14", :type => "text"}/
                  :javascript
                    jQuery('#flavor_score').slider({
                    	formatter: function(value) {
                    		return 'Current value: ' + value;
                    	}
                    });  
              .point.vertical
                .question
                  What is your overall opinion of 90+ Protein?
                .answer
                  %textarea.form-control{:rows => "3"}
                .submit.text-center
                  %button.btn.btn-xs.btn-primary Submit Review
            
@stop