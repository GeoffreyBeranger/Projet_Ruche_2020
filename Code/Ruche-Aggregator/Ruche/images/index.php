<?php

// Une redirection vers le répertoire précédent
// qui redirigera lui-même vers le précédent jusqu'à trouver une
// page autorisée

header("location:../");
exit();
