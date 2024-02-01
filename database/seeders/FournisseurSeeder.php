<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Insérer trois fournisseurs
         Fournisseur::create([
            'nom_entreprise' => 'Fournisseur 1',
            'nom_contact' => 'Contact 1',
            'adresse' => 'Adresse 1',
            'telephone' => '1234567891',
            'email' => 'fournisseur1@example.com',
            'site_web' => 'http://www.fournisseur1.com',
            'numero_tva' => 'TVA123',
            'notes' => 'Notes fournisseur 1',
            'ville' => 'Paris',
            'code_postal' => '75000',
        ]);

        Fournisseur::create([
            'nom_entreprise' => 'Fournisseur 2',
            'nom_contact' => 'Contact 2',
            'adresse' => 'Adresse 2',
            'telephone' => '1234567892',
            'email' => 'fournisseur2@example.com',
            'site_web' => 'http://www.fournisseur2.com',
            'numero_tva' => 'TVA456',
            'notes' => 'Notes fournisseur 2',
            'ville' => 'Lyon',
            'code_postal' => '69000',
        ]);

        Fournisseur::create([
            'nom_entreprise' => 'Fournisseur 3',
            'nom_contact' => 'Contact 3',
            'adresse' => 'Adresse 3',
            'telephone' => '1234567893',
            'email' => 'fournisseur3@example.com',
            'site_web' => 'http://www.fournisseur3.com',
            'numero_tva' => 'TVA789',
            'notes' => 'Notes fournisseur 3',
            'ville' => 'Marseille',
            'code_postal' => '13000',
        ]);
    }
}
