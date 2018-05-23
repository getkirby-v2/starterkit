<div class="debugger-hooks">
	<div class="debugger-hooks-excerpt">
		<div class="debugger-hooks-icon">
			<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
		</div>
		<div class="debugger-hooks-excerpt-info">
			<div class="debugger-hooks-title">
				<?php echo $type ?> on line <?php echo $line; ?>
			</div>

			<div class="debugger-hooks-time">
				<?php echo date('Y-m-d, H:i:s', $time); ?><br>
			</div>
		</div>
	</div>
	
	<div class="debugger-hooks-message">
		<?php echo $message; ?>
	</div>

	<div class="debugger-hooks-file">
		<?php echo $file; ?>
	</div>

	<div class="debugger-hooks-close">
		<a target="_top" href="<?php echo u() . '/plugin.hooks.debugger.log.clear/' . $page->id(); ?>">
			<i class="fa fa-times" aria-hidden="true"></i>
		</a>
	</div>
</div>