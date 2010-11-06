		<?php if (Auth::instance()->logged_in()) {?>
				<?php if (Kohana::$environment === Kohana::DEVELOPMENT){?>
					<a href="#application-profiler">profiler</a>
				<?php }?>
				<a href="<?php echo URL::site('feedback') ?>">feedback</a>
				<a href="<?php echo URL::site('sign-out') ?>">sign out</a>
		<?php } else {?>
				<a href="<?php echo URL::site('info') ?>">info</a>
				<a href="<?php echo URL::site('contact')?>">contact</a>
		<?php } ?>
