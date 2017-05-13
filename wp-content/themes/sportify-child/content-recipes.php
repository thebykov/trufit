<div class="blog-item col-md-6 col-sm-12 col-xs-12 no-margin">

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

                <ul class="inline-list post-meta-list row">

                    <li class="col-md-4 col-xs-5 no-padding"><a class="text-red hover-text-dark-red blog-post-date date"><?php the_time('d M') ?></a></li>

                    <li class="col-md-6 col-xs-5 no-padding"><a href="#" class="text-red hover-text-dark-red author"><?php the_author_link(); ?></a></li>

                    <li class="col-md-2 col-xs-2 no-padding">

                        <a class="meta-link comments" href="<?php the_permalink(); ?>"><?php comments_number( '0', '1', '%' ) ?></a>

                    </li>

                </ul>



                <h4 class="entry-header text-center padding"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4><div class="tags-list post-tags-list"><?php the_terms( $post->ID, 'recipe_tags', '', ', ', ' ' ); ?></div>

            </header>
<br>



            <!--
<hr />
<div class="blog-content padding">

                <?php 

                    //apply_filters('the_excerpt', the_excerpt());

                ?>

            </div>-->

        </div>

    </article>

</div>