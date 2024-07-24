<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        <?php include "style.css"; ?>
    </style>
</head>

<body>
    <?php include_once ("assets/navbar.php"); ?>

    <div class="container my-4">
        <div class="row">
            <h1>Főoldal</h1><a href=""></a>
            <div class="col-12 col-lg-6 my-2">
                <p>Ez egy PHP-ban megírt MySQL adatbázissal ellátott fullstack weboldal!</p>
                <p>A <a href="controller.php?page=productSummaryView">Szervizelt termékek</a> menüpont alatt lehet megnézni a képzeletbeli szervíz oldalán lévő szervizelt termékek adatait és állapotukat.</p>
                <p>Ugyanezen fül alatt lehet módosítani is a termékek adatait és állapotukat. Törlésre is itt van lehetőség. A különböző háttérszínek jelzik a termékek aktuális állapotait.</p>
                <p>A <a href="controller.php?page=contactSummaryView">Kapcsolattartók</a> menüpont alatt lehet megnézni a szervizelt termékekhez kapcsolodó virtuális kapcsolattartókat. A Termék és a Kapcsolattartó ID-ja van összekapcsolva. A kapcsolattartót is a "Szervizelt termékek" menüpont alatt lehet módosítani, ha igény van rá. </p>
                <p>A <a href="controller.php?page=newProductView">Termék leadása szervizbe</a> menüpont alatt lehet új terméket-kapcsolattartót rögzíteni, ami automatikus mentésre kerül az SQL adatbázisba. Az új termék állapota automatikusan "Beérkezett" állapotú, melyet a fent említett "Szervizelt termékek"-nél lehet módosítani.</p>
                <p>A "Kész" státuszú termék csak aznap látszódik a táblázatban amely nap lett kész, utána már csak az adatbázisban érhető el.</p>
            </div>
            <div class="col-12 col-lg-6 my-2">
                <a href="controller.php?page=productSummaryView"><img src="/Vizsgafeladat/view/assets/pics/service.jpg"
                        alt="PHP MVC Szervíz oldal" title="PHP MVC Szervíz oldal" id="pic" class="w-100 mt-4"></a>
            </div>
        </div>
    </div>

    <?php include_once ("assets/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>