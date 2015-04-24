
  <div class="beContentEntry">
    <h1 class="beContentEntryHeader">Über beusterse.de</h1>
    <p>
      Es war der 28. März 2010, als dieser Blog  aus der Taufe gehoben wurde. Ein wirkliches Ziel war nicht vorhanden, sondern es schlichtweg um das
      Probieren der Technik selbst und eine weitere Präsentationsfläche neben den YouTube-Videos.
    </p>
    <p>
      Dieser Blog ist aus anderen Projekten hervorgegangen, die mich während der Schulzeit begleiteten. Die meisten verliefen im Sande, im Rahmen der
      Facharbeit hatte ich waterwebdesign.de ins Leben gerufen, sah mich nach einiger Zeit, dann thematisch doch recht eingeschränkt.
    </p>
    <p>
      Mit der Zeit hat sich hier dann doch viel getan. Ich habe viel gelernt und so kamen und verschwanden verschiedene Funktionen, und die ein oder
      andere nervenaufreibende Stunde im Code gab es sicherlich auch.
      Mit ein wenig Stolz blicke ich auf das Geschaffte zurück, aber vor allem nach vorne, wer weiß, wo die Reise hingeht.
    </p>
    <p>
      Wenn du Feedback, deine Meinung oder einfach nur eine Frage loswerden möchtest, dann nutze doch einfach das untenstehende Formular.
    </p>
  </div>
  <div id="beContentEntryComments">
    <h2 class="beContentEntryCommentsHeader">Hast du Feedback?</h2>
    <div class="beCommentNew"><?php
      $err = array('t' => $data['eType'], 'c' => $data['ec']);
      echo genFormpublic($err, '/about', $data['time'], '', 'Gib mir Feedback!', 'feedbackForm'); ?>
    </div>
  </div>
