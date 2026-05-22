<?php

namespace App\Tests\Entity;

use App\Entity\Bien;
use PHPUnit\Framework\TestCase;

class BienTest extends TestCase
{
    public function testDefaultStatutIsDisponible(): void
    {
        $bien = new Bien();
        $this->assertSame(Bien::STATUT_DISPONIBLE, $bien->getStatut());
    }

    public function testDateCreationInitializedOnConstruct(): void
    {
        $bien = new Bien();
        $this->assertInstanceOf(\DateTimeInterface::class, $bien->getDateCreation());
    }

    public function testStatutCanBeChanged(): void
    {
        $bien = new Bien();
        $bien->setStatut(Bien::STATUT_VENDU);
        $this->assertSame(Bien::STATUT_VENDU, $bien->getStatut());
    }
}
