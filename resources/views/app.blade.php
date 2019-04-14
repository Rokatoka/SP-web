<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Загрузка...</title>
    <link rel="stylesheet" href="{{ asset("/css/main.css?".random_int(1000, 9999)) }}">
    <link rel="stylesheet" href="{{ asset("/css/app.css") }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="client-secret" content="4jLRZQcrbHjAOH58B71ZUz4XrDmxhygoGFsLszqq">

</head>
<body>
<div id="app">
    <div v-bind:class="{ 'bg-faded': !user }">

        <sidebar v-if="user"></sidebar>
        <transition name="fade" mode="out-in">
            <router-view v-if="
            (user && user.data && $route.meta.forAuth) || ($route.meta.forVisitors)"></router-view>
        </transition>

    </div>
</div>


<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
<script src="{{ asset("/js/app.js?".random_int(1000, 9999)) }}" charset="utf-8"></script>
<script src="https://unpkg.com/chart.js@2.7.2/dist/Chart.bundle.js"></script>
<script src="https://unpkg.com/vue-chartkick@0.5.0"></script>



</body>
</html>