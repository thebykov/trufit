<div class="single-member col-md-12 col-sm-12 col-xs-12 no-margin">
<article class="blog-item list-slyle">
  <?php if( has_post_thumbnail() ): ?>
  <figure class="ovh">
    <?php 

                    $post_thumbnail_id = get_post_thumbnail_id(); 

                    $post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );

                ?>
    <figcaption class="hover-scale-110 transition-medium"> <a href="<?php the_permalink(); ?>" style="background: url('<?php echo $post_thumbnail_url; ?>') no-repeat 50% 0 transparent; background-size: cover;" ></a> </figcaption>
  </figure>
  <?php endif; ?>
  <div class="grey-background">
    <header class="blog-head">
     <h2 class="entry-header text-center padding">
        <?php the_title(); ?>
      </h2>
    </header>
    <hr />
    <div class="blog-content white-background padding">
        <ul class="nav nav-tabs nav-justified">
          <li class="active"><a data-toggle="tab" href="#nutrition">Nutrition</a></li>
          <li><a data-toggle="tab" href="#menu1">Menu 1</a></li>
          <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
          <li><a data-toggle="tab" href="#menu3">Menu 3</a></li>
        </ul>
        <div class="tab-content">
          <div id="nutrition" class="tab-pane fade in active">
              <?php acf_form(); ?>
          </div>
          <div id="menu1" class="tab-pane fade">
            <div>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
          </div>
          <div id="menu2" class="tab-pane fade">
            <div>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</div>
          </div>
          <div id="menu3" class="tab-pane fade">
            <div>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
          </div>
        </div>
    </div>
  </div>
</article>
</div>
