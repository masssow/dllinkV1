{% extends 'base.html.twig' %}

{% block title %}Session {{ session.title }} - DahiraLink{% endblock %}

{% block body %}

{% set total = session.totalTarget ?? 0 %}
{% set completed = completedCount ?? 0 %}
{% set percent = total > 0 ? (completed / total * 100)|round : 0 %}

<section class="dl-session-hero py-5">

<div class="container">

<div class="text-center">

<span class="dl-eyebrow">Session spirituelle</span>

<h1 class="dl-session-title mt-2">
{{ session.title }}
</h1>

<p class="dl-session-meta">

{{ session.type }}

{% if session.scheduledAt %}
• {{ session.scheduledAt|date('d/m/Y H:i') }}
{% endif %}

</p>

{% if session.description %}
<p class="dl-session-description mt-3">
{{ session.description }}
</p>
{% endif %}

</div>

</div>

</section>

<section class="pb-5">

<div class="container">

<div class="row g-4">

<div class="col-lg-8">

<div class="dl-card">

{% include 'quran_session/components/khatm_grid.html.twig' %}

</div>

<div class="dl-card mt-4">

{% include 'quran_session/components/khatm_progress.html.twig' %}

</div>

</div>

<div class="col-lg-4">

<div class="dl-card">

{% include 'quran_session/components/session_header.html.twig' %}

</div>

</div>

</div>

</div>

</section>

<script>

window.khatmConfig = {
    sessionSlug: "{{ session.slug }}",
    sessionStateUrl: "{{ path('app_session_state', {'slug': session.slug}) }}",
    toggleUrlBase: "/api/hizb/toggle/"
}

</script>

<script src="/js/khatmAnimation.js"></script>

{% endblock %}