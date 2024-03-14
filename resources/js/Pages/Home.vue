<script setup></script>

<template>
    <Head title="Game Discover - Home" />

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <!-- Header -->
            <header>
                <img src="logo.png" alt="Logo" />
                <nav>
                    <ul>
                        <li><a href="#">Recherche de jeux</a></li>
                        <li><a href="#">Genres</a></li>
                        <li><a href="#">Plateformes</a></li>
                    </ul>
                </nav>
                <button>Connexion</button>
                <button>Inscription</button>
            </header>

            <!-- Hero section -->
            <section class="hero">
                <h1>Découvrez votre prochaine aventure</h1>
                <p>Une brève description de votre application.</p>
                <button>Commencer à explorer</button>
            </section>

            <!-- Main features -->
            <section class="features">
                <h2>Fonctionnalités principales</h2>
                <div class="feature">
                    <img src="search.png" alt="Recherche de jeux" />
                    <h3>Recherche de jeux</h3>
                    <p>
                        Effectuez des recherches pour trouver vos jeux préférés.
                    </p>
                </div>
                <div class="feature">
                    <img src="reviews.png" alt="Évaluations et critiques" />
                    <h3>Évaluations et critiques</h3>
                    <p>
                        Lisez les avis des utilisateurs et donnez votre propre
                        avis.
                    </p>
                </div>
                <div class="feature">
                    <img
                        src="suggestions.png"
                        alt="Suggestions personnalisées"
                    />
                    <h3>Suggestions personnalisées</h3>
                    <p>
                        Obtenez des recommandations de jeux basées sur vos
                        préférences.
                    </p>
                </div>
            </section>

            <!-- Registration -->
            <section class="registration">
                <h2>Inscription</h2>
                <p>
                    Accédez aux évaluations, aux critiques, aux listes de
                    souhaits, etc.
                </p>
                <button>Inscription</button>
            </section>

            <!-- Footer -->
            <footer>
                <ul>
                    <li><a href="#">Mentions légales</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                    <li><a href="#">Réseaux sociaux</a></li>
                </ul>
            </footer>
        </div>
    </div>
</template>
