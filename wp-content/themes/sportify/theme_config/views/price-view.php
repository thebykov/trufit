<?php $max = floor(12/(int)$shortcode['size']); ?>

<section  id="pricing_box" class="box pricing-box"><!-- Section Events -->
  <header class="text-center pricing-box-header box-header">
      <div class="container">
          <div class="white-border box-header-title-block aligncenter nowrap">
              <h2 class="entry-header"><?php echo $shortcode['title'] ?></h2>
          </div>
      </div>
  </header>
  <div class="container">
    <div class="row">
      <?php foreach($slides as $i => $slide): ?>
          <div class="col-md-4 col-sm-4">
              <div class="pricing-table dark-yellow text-white hover-scale-115 transition-short <?php echo in_array('outlined', $slide['options']['outlined']) ? 'scale-115 outlined' : ''; ?>">
                <div class="white-background">
                  <h2 class="text-center text-white entry-header light-green-background"> <?php echo $slide['post']->post_title; ?></h2>
                  
                  <div class="row padding">
                    <div class="col-md-4">
                      <span class="price hard-corners center-me blue font-2x text-dark-blue"><?php echo $slide['options']['price']; ?></span>
                    </div>
                    <div class="col-md-8">
                      <div class="white-background">
                        <a class="read-more text-center light-green-hover" href="<?php echo $slide['options']['link'] ?>"><?php echo $slide['options']['link_text'] ? $slide['options']['link_text'] : __('Buy Now','sportify')?></a>
                      </div>
                    </div>
                  </div>
                  
                </div>

                <ul class="clean-list light-grey-background">
                  <?php foreach($slide['options']['features'] as $feature): ?>
                    <li><i class="icon-455 font-small text-blue"></i><?php echo do_shortcode($feature); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>