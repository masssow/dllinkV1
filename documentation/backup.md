<footer class="footer-area">
            <div class="container">

                <div class="row gy-4">

                    <div class="col-md-4">

                        <div class="footer-brand">
                            <img src="{{ asset('images/logo-dahiralink.png') }}" height="40">
                            <h5 class="mt-2">DahiraLink</h5>
                            <p>
                            Plateforme dédiée aux dahiras pour organiser les lectures collectives du Coran,
                            suivre les khatm et renforcer les liens communautaires.
                            </p>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <h6 class="footer-title">Navigation</h6>

                        <ul class="footer-links">

                            <li><a href="{{ path('app_home') }}">Accueil</a></li>

                            <li><a href="{{ path('app_quran_session_new') }}">Créer un Khatm</a></li>

                            {# <li><a href="{{ path('app_session_join') }}">Rejoindre une session</a></li> #}

                            <li><a href="#">Dahiras</a></li>

                        </ul>

                    </div>

                    <div class="col-md-4">

                        <h6 class="footer-title">Informations</h6>

                                    <ul class="footer-links">

                                        <li><a href="{{ path('app_about') }}">À propos</a></li>

                                        <li><a href="{{ path('app_contact') }}">Contact</a></li>

                                        <li><a href="{{ path('app_legalicy') }}">Mentions légales</a></li>

                                    </ul>

                    </div>

                </div>

                <hr class="my-4">

                <div class="text-center mb-2">

                    <small class="text-muted">

                    Images utilisées sur ce site :

                    <br>

                    Photo par 
                    <a href="https://pixabay.com/fr/users/joko_narimo-3032599/" target="_blank">
                    Joko Narimo
                    </a> — Pixabay

                    <br>

                    Photo par 
                    <a href="https://pixabay.com/users/abdullah_shakoor-123456/" target="_blank">
                    Abdullah Shakoor
                    </a> — Pixabay

                    <br>

                    Photo par 
                    <a href="https://pixabay.com/fr/users/kolaoltion-16160874/" target="_blank">
                    Pexels
                    </a> Kolaoltion — Pixabay

                    </small>

                </div>

                <div class="text-center">

                    <p class="mb-0">

                    © {{ "now"|date("Y") }} DahiraLink — Tous droits réservés

                    </p>

                </div>

            </div>
        </footer>