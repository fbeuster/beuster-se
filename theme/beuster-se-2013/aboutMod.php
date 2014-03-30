
  <article class="beContentEntry">
  	<h1 class="beContentEntryHeader">Über den Autor des Artikels</h1>
  	<p>
	<?php
 	if(isset($data['err'])) {
  		echo $data['err'].' ist kein Autor auf <a href="http://beusterse.de">beusterse.de</a>.';
 	} else { ?>
   	<img src="/images/mods/<?php echo $data['about']->getName(); ?>.jpg" class="about"><?php echo $data['about']->buildInfo(); 
 	} ?>
  	</p>
  </article>
  