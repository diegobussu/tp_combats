<?php
/*
$menu = new MenuManager();
$listMenu = $menu->getMenu();

foreach( $listMenu as $cle=>$menu ) {
    $nomScript = '#';
    if(  !empty( $menu['scriptPoo'] ) ) {
        $nomScript = $menu['scriptPoo'];
    }
    echo '<a class="nav-link" href="' . $nomScript . '">' . $menu['id'] . ' - ' . $menu['nom'] . '</a>';
} */
?>
<ul class="nav justify-content-start">
    <li class="nav-item">
        <a class="nav-link" href="?controller=index"> Accueil</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?controller=jouer"> Jouer</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?controller=personnages"> Liste des personnages</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?controller=index&action=logout"> Se deconnecter</a>
    </li>
</ul>