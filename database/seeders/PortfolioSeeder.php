<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Experience;
use App\Models\Interest;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Profile::updateOrCreate([], [
            'full_name' => 'Héctor Rojas Barcenas',
            'headline_es' => 'Desarrollador Full Stack',
            'headline_en' => 'Full Stack Developer',
            'bio_es' => 'Desarrollador enfocado en construir aplicaciones web con Laravel, Livewire y JavaScript. Me apasiona escribir código limpio y resolver problemas reales.',
            'bio_en' => 'Developer focused on building web applications with Laravel, Livewire and JavaScript. I care about writing clean code and solving real problems.',
            'email' => 'rojasbarcenash@gmail.com',
            'location' => 'México',
            'github_url' => 'https://github.com',
            'linkedin_url' => 'https://linkedin.com',
            'available_for_hire' => true,
        ]);

        $technologies = collect([
            'PHP', 'Laravel', 'Livewire', 'JavaScript', 'Tailwind CSS', 'MySQL', 'Vue.js',
        ])->mapWithKeys(fn (string $name) => [
            $name => Technology::firstOrCreate(['slug' => str($name)->slug()], ['name' => $name]),
        ]);

        $companyOne = Company::firstOrCreate(
            ['slug' => 'tech-solutions'],
            ['name' => 'Tech Solutions', 'location' => 'Remoto']
        );

        $companyTwo = Company::firstOrCreate(
            ['slug' => 'freelance'],
            ['name' => 'Freelance', 'location' => 'Remoto']
        );

        $experienceOne = Experience::updateOrCreate(
            ['company_id' => $companyOne->id, 'position_es' => 'Desarrollador Full Stack'],
            [
                'position_en' => 'Full Stack Developer',
                'employment_type' => 'full_time',
                'description_es' => 'Desarrollo y mantenimiento de aplicaciones web con Laravel y Livewire para clientes de distintas industrias.',
                'description_en' => 'Development and maintenance of web applications with Laravel and Livewire for clients across different industries.',
                'is_remote' => true,
                'start_date' => now()->subYears(2),
                'is_current' => true,
            ]
        );
        $experienceOne->technologies()->sync($technologies->only(['PHP', 'Laravel', 'Livewire', 'MySQL'])->pluck('id'));

        $experienceTwo = Experience::updateOrCreate(
            ['company_id' => $companyTwo->id, 'position_es' => 'Desarrollador Web'],
            [
                'position_en' => 'Web Developer',
                'employment_type' => 'freelance',
                'description_es' => 'Construcción de sitios y aplicaciones a medida para pequeños negocios.',
                'description_en' => 'Building custom sites and applications for small businesses.',
                'is_remote' => true,
                'start_date' => now()->subYears(4),
                'end_date' => now()->subYears(2),
            ]
        );
        $experienceTwo->technologies()->sync($technologies->only(['JavaScript', 'Tailwind CSS', 'Vue.js'])->pluck('id'));

        $projectOne = Project::updateOrCreate(
            ['slug' => 'portafolio-personal'],
            [
                'title' => 'Portafolio Personal',
                'context' => 'personal',
                'excerpt_es' => 'Sitio web para mostrar mis proyectos y experiencia laboral, construido con Laravel y Livewire.',
                'excerpt_en' => 'Website to showcase my projects and work experience, built with Laravel and Livewire.',
                'status' => 'in_progress',
                'is_featured' => true,
                'sort_order' => 1,
                'started_at' => now()->subMonth(),
            ]
        );
        $projectOne->technologies()->sync($technologies->only(['Laravel', 'Livewire', 'Tailwind CSS'])->pluck('id'));

        $projectTwo = Project::updateOrCreate(
            ['slug' => 'gestor-de-tareas'],
            [
                'title' => 'Gestor de Tareas',
                'context' => 'freelance',
                'excerpt_es' => 'Aplicación para organizar tareas y proyectos personales con tableros estilo kanban.',
                'excerpt_en' => 'Application to organize personal tasks and projects with kanban-style boards.',
                'status' => 'completed',
                'is_featured' => false,
                'sort_order' => 2,
                'started_at' => now()->subYear(),
                'finished_at' => now()->subMonths(8),
            ]
        );
        $projectTwo->technologies()->sync($technologies->only(['PHP', 'Laravel', 'MySQL'])->pluck('id'));

        collect([
            ['name_es' => 'Código abierto', 'name_en' => 'Open source', 'icon' => 'code-bracket', 'description_es' => 'Contribuir y mantener proyectos open source.', 'description_en' => 'Contributing to and maintaining open source projects.'],
            ['name_es' => 'Fotografía', 'name_en' => 'Photography', 'icon' => 'camera', 'description_es' => 'Explorar la fotografía urbana y de paisajes.', 'description_en' => 'Exploring urban and landscape photography.'],
            ['name_es' => 'Ajedrez', 'name_en' => 'Chess', 'icon' => 'puzzle-piece', 'description_es' => 'Jugar y estudiar aperturas en mi tiempo libre.', 'description_en' => 'Playing and studying openings in my free time.'],
        ])->each(fn (array $interest) => Interest::updateOrCreate(['name_es' => $interest['name_es']], $interest));
    }
}
