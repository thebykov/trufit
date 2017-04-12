<div class="blog-item col-md-2 col-sm-4 col-xs-6 no-margin">

    <article class="blog-item list-slyle">

        <?php if( has_post_thumbnail() ): ?>

            <figure class="ovh">

                <?php 

                    $post_thumbnail_id = get_post_thumbnail_id(); 

                    $post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );

                ?>

                <figcaption class="hover-scale-110 transition-medium">

                    <a href="<?php the_permalink(); ?>" style="background: url('<?php echo $post_thumbnail_url; ?>') no-repeat 50% 0 transparent; background-size: cover;" ></a>

                </figcaption>

            </figure>

        <?php endif; ?>

        

        <div class="grey-background">

            <header class="blog-head">


                <h4 class="entry-header text-center padding"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4><div class="tags-list post-tags-list"><?php the_tags('', ', ', ''); ?></div>

            </header>           

        </div>

    </article>

</div>