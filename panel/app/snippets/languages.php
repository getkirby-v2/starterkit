<?php if($languages): ?>

<div class="languages">

  <a id="languages-toggle" class="languages-toggle" data-dropdown="true" href="#languages">
    <span><?php __($language->code()) ?></span>
  </a>

  <nav id="languages" class="dropdown dropdown-left">
    <ul class="nav nav-list dropdown-list">
      <?php foreach($languages as $lang): ?>
      <li>
        <a href="?language=<?php echo $lang->code() ?>"><?php __(strtoupper($lang->code())) ?></a>
      </li>
      <?php endforeach ?>
    </ul>
  </nav>

</div>

<?php endif ?>
