<div class="bars bars-with-sidebar-left cf">

  <aside class="sidebar sidebar-left">

    <a class="sidebar-toggle" href="#sidebar" data-hide="<?php _l('options.hide') ?>"><span><?php _l('options.show') ?></span></a>

    <div class="sidebar-content">

      <div class="section">

        <?php if(kirby()->option('panel.info.versions')): ?>
        <h2 class="hgroup hgroup-single-line hgroup-compressed cf">
          <span class="hgroup-title">
            <?php _l('metatags.info') ?>
          </span>
        </h2>
        <div class="text">
          <p> 
            <?php _l('metatags.version.toolkit') ?>: <?php echo toolkit::version() ?><br />
            <?php _l('metatags.version.kirby') ?>: <?php echo kirby::version() ?><br />
            <?php _l('metatags.version.panel') ?>: <?php echo panel::version() ?>
          </p>
        </div>
        <?php endif ?>

        <?php if(kirby()->option('panel.info.license')): ?>
        <h2 class="hgroup hgroup-single-line hgroup-compressed cf">
          <span class="hgroup-title">
            <?php _l('metatags.license') ?>
          </span>
        </h2>
        <div class="text">
          <?php if($license->type() == 'trial' and !$license->local()): ?>
          <p>
            You are using the Kirby trial version<br />
            <a target="_blank" href="http://getkirby.com/buy">Please, buy a license &rsaquo;</a>
          </p>
          <?php else: ?>
          <p> 
            <?php echo $license->type() ?><br />
            <em class="marginalia" style="font-size: .9em"><?php echo $license->key() ?></em>
          </p>
          <?php endif ?>
        </div>
        <?php endif ?>

        <?php echo $files ?>
      </div>

    </div>

  </aside>

  <div class="mainbar">
    <div class="section">
      <?php echo $form ?>
    </div>
  </div>

</div>

<?php echo $uploader ?>