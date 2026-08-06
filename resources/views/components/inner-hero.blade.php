@props(['title', 'crumb', 'text', 'image'])

<div class="inner-hero">
    <div class="hero-bg" style="--image: url('{{ $image }}');"></div>
    <div class="hero-content">
        <h1>{{ $title }}</h1>
        <span class="breadcrumb">{{ $crumb }}</span>
        <p>{{ $text }}</p>
    </div>
</div>
