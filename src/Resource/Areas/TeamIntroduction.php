<?php

namespace App\Resource\Areas;

use Pimcore\Model\Document\Editable\Area\AbstractArea;

class TeamIntroduction extends AbstractArea
{
public function action()
    {
        // Sie können hier zusätzliche Logik hinzufügen, wenn Sie möchten
    }

    public function getName()
    {
        return "Team Introduction";
    }

    public function getDescription()
    {
        return "Ein Bereich, der ein Teammitglied mit einem Foto und einem kurzen Text darstellt";
    }
}
