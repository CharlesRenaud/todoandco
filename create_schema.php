<?php
// create_schema.php
use Doctrine\ORM\Tools\SchemaTool;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/app/AppKernel.php';

// Démarrage du kernel Symfony
$kernel = new AppKernel('dev', true);
$kernel->boot();

// Récupération de l'EntityManager
$em = $kernel->getContainer()->get('doctrine')->getManager();

// Récupération de toutes les entités
$metadatas = $em->getMetadataFactory()->getAllMetadata();

if (empty($metadatas)) {
    echo "Aucune entité trouvée.\n";
    exit;
}

// Création / mise à jour du schéma
$tool = new SchemaTool($em);

try {
    $tool->updateSchema($metadatas, true); // true = force, crée toutes les tables
    echo "Toutes les tables ont été créées/mises à jour avec succès !\n";
} catch (\Exception $e) {
    echo "Erreur lors de la création du schéma : " . $e->getMessage() . "\n";
}
