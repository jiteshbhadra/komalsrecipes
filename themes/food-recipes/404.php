<?php 
/*
* 404 page template
*/
get_header(); ?>

<div class="page-title">
  <div class="container">
    <div class="row">
      <div class="col-md-6  col-sm-6 ">
        <h1><?php _e('404 Page','foodrecipes'); ?></h1>
      </div>
      <div class="col-md-6  col-sm-6 ">
        <ol class="archive-breadcrumb  pull-right">
          <?php if (function_exists('foodrecipes_custom_breadcrumbs')) foodrecipes_custom_breadcrumbs(); ?>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="foodrecipes-container-recipes container">
    <div class="row">
      <div class="col-md-12 main no-padding">
        <div class="jumbotron">
          <h1><?php _e('Epic 404 - Article Not Found','foodrecipes'); ?></h1>
          <p><?php _e("This is embarassing. We can't find what you were looking for.","foodrecipes"); ?></p>
          <section class="post_content">
            <p><?php _e('Whatever you were looking for was not found, but maybe try looking again or search using the form below.','foodrecipes'); ?></p>
            <div class="row">
              <div class="col-sm-12">
                <?php get_search_form(); ?>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
