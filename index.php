<?php
$ch = curl_init("http://localhost/recipe_planner/api/v1/recipes/listall.php");

curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_BASIC);
curl_setopt($ch,CURLOPT_USERPWD,"Abie:recipe@PLAN");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$response = curl_exec($ch);
$recipes = [];
if($error){
  $recipes = [];
}else{
  $rec = json_decode($response);
  $recipes =$rec->data;
 
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FreshPlate — Recipe & Meal Planner</title>

<link href="assets/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

<style>

  :root {
    --bg: #FAF6F0;
    --surface: #FFFFFF;
    --text: #2B2420;
    --text-soft: #7A6F63;
    --line: #ECE3D6;
    --primary: #FF6B4A;
    --primary-dark: #E5573A;
    --gold: #F0B429;
    --teal: #2F9E8F;

    --bs-body-bg: var(--bg);
    --bs-body-color: var(--text);
    --bs-border-color: var(--line);
    --bs-primary: var(--primary);
    --bs-primary-rgb: 255,107,74;
    --bs-secondary-color: var(--text-soft);
    --bs-tertiary-bg: var(--surface);
  }

  /*  dark mode:  */
  body:has(#darkSwitch:checked) {
    --bg: #17140F;
    --surface: #221D17;
    --text: #F4EEE4;
    --text-soft: #B3A797;
    --line: #372F26;
    --bs-body-bg: var(--bg);
    --bs-body-color: var(--text);
    --bs-border-color: var(--line);
    --bs-tertiary-bg: var(--surface);
  }

  body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); transition: background .2s ease, color .2s ease; }
  h1, h2, h3, .brand { font-family: 'Poppins', sans-serif; }
  a { text-decoration: none; }

  /* ---------- navbar ---------- */
  .navbar { background: var(--surface); border-bottom: 1px solid var(--line); }
  .brand { font-weight: 800; font-size: 1.4rem; color: var(--text) !important; }
  .brand span { color: var(--primary); }
  .nav-link { color: var(--text-soft) !important; font-weight: 500; }
  .nav-link:hover { color: var(--primary) !important; }


  #navToggle { display: none; }
  .nav-toggle-label {
    display: none;
    font-size: 1.4rem;
    color: var(--text);
    cursor: pointer;
  }
  .nav-collapse { display: flex; align-items: center; gap: .25rem; }
  @media (max-width: 767.98px) {
    .nav-toggle-label { display: block; }
    .nav-collapse {
      display: none;
      flex-direction: column;
      align-items: stretch;
      width: 100%;
      padding-top: .75rem;
    }
    body:has(#navToggle:checked) .nav-collapse { display: flex; }
  }

  /* ---------- dark mode switch ---------- */
  .mode-switch { display: flex; align-items: center; gap: .5rem; font-size: .8rem; color: var(--text-soft); }
  .form-switch .form-check-input { width: 2.3em; height: 1.2em; cursor: pointer; }

  /* ---------- hero ---------- */
  .hero {
    position: relative;
    min-height: 420px;
    display: flex;
    align-items: center;
    background: url('assets/static/images/veggies.jpg') center/cover no-repeat;
  }
  .hero::before {
    content: "";
    position: absolute; inset: 0;
    background: linear-gradient(100deg, rgba(20,16,12,.82) 20%, rgba(20,16,12,.45) 65%, rgba(20,16,12,.15) 100%);
  }
  .hero-content { position: relative; z-index: 1; color: #fff; max-width: 620px; }
  .hero-badge {
    display: inline-block;
    background: var(--gold);
    color: #241D0E;
    font-weight: 600;
    font-size: .8rem;
    padding: .4rem 1rem;
    border-radius: 3px 14px 3px 14px;
    margin-bottom: 1.1rem;
  }
  .hero-content h1 { font-weight: 800; font-size: 2.9rem; line-height: 1.15; }
  .hero-content p.lead { color: #EDE7DD; }
  .btn-hero {
    background: var(--primary);
    border: none;
    color: #fff;
    font-weight: 600;
    padding: .7rem 1.7rem;
    border-radius: 4px 16px 4px 16px;
  }
  .btn-hero:hover { background: var(--primary-dark); color: #fff; }

  /* ---------- section titles ---------- */
  .section-title { font-weight: 700; margin-bottom: .3rem; }
  .section-sub { color: var(--text-soft); margin-bottom: 1.8rem; }

  /* ---------- recipe cards ---------- */
  .recipe-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 8px 8px 20px 8px;
    overflow: hidden;
    height: 100%;
    transition: transform .15s ease, box-shadow .15s ease;
  }
  .recipe-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,.08); }
  .recipe-card img { height: 170px; object-fit: cover; width: 100%; }
  .recipe-card .card-body { padding: 1.1rem 1.2rem 1.3rem; }
  .badge-tag {
    background: rgba(47,158,143,.12);
    color: var(--teal);
    font-weight: 600;
    font-size: .68rem;
    padding: .3em .7em;
    border-radius: 16px;
    margin: 0 .3em .3em 0;
  }
  .meta-row { color: var(--text-soft); font-size: .8rem; }
  .meta-row i { margin-right: .25rem; }

  /* ---------- table ---------- */
  .plan-table th { background: var(--surface); font-weight: 600; font-size: .82rem; color: var(--text-soft); }
  .plan-table td, .plan-table th { border-color: var(--line) !important; vertical-align: middle; }
  .plan-table tbody tr:hover { background: rgba(255,107,74,.04); }
  .meal-tag { font-weight: 600; font-style: italic; color: var(--text-soft); }

  /* ---------- shopping list ---------- */
  .shop-item { border-color: var(--line) !important; background: var(--surface) !important; color: var(--text) !important; }
  .shop-item label { cursor: pointer; }

  /* ---------- form card ---------- */
  .form-card { background: var(--surface); border: 1px solid var(--line); border-radius: 10px; padding: 2rem; }
  .form-control, .form-select {
    background: var(--bg);
    border-color: var(--line);
    color: var(--text);
  }
  .form-control:focus, .form-select:focus {
    background: var(--bg);
    color: var(--text);
    border-color: var(--primary);
    box-shadow: 0 0 0 .2rem rgba(255,107,74,.15);
  }

  footer { 
  border-top: 1px solid var(--line);
  color: var(--text-soft);
  font-size: .85rem;
  text-align: center;
  
  }
</style>
</head>
<body>

<nav class="navbar navbar-expand-md py-3">
  <div class="container">
    <a class="brand" href="#">Fresh<span>Plate</span></a>

    <label for="navToggle" class="nav-toggle-label"><i class="bi bi-list"></i></label>
    <input type="checkbox" id="navToggle" style="display:none;">

    <div class="nav-collapse ms-auto">
      <a class="nav-link px-3" href="#recipes">Recipes</a>
      <!-- <a class="nav-link px-3" href="#meal-plan">Meal plan</a>
      <a class="nav-link px-3" href="#shopping">Shopping list</a> -->
      <a class="nav-link px-3" href="#add-recipe">Add recipe</a>
      <!-- dark mode toggle: a real checkbox, styled as a switch.
           #darkSwitch:checked is read directly by body:has(#darkSwitch:checked)
           in the CSS above — no JavaScript involved. -->
      <label class="mode-switch ms-md-3 mt-2 mt-md-0" for="darkSwitch">
        <i class="bi bi-sun"></i>
        <span class="form-check form-switch mb-0">
          <input class="form-check-input" type="checkbox" role="switch" id="darkSwitch">
        </span>
        <i class="bi bi-moon-stars"></i>
      </label>
    </div>
  </div>
</nav>

<!-- ===================== HERO ===================== -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <span class="hero-badge">Plan smarter. Eat better.</span>
      <h1>Your delicious week starts here.</h1>
      <p class="lead my-3">Discover recipes, organize your meals, and keep your weekly menu on track — all in one place.</p>
      <a href="#recipes" class="btn btn-hero">Explore recipes</a>
    </div>
  </div>
</section>

<main class="container py-5">

  <!-- ===================== RECIPES (cards) ===================== -->
  <section id="recipes" class="mb-5">
    <h2 class="section-title">Recipes</h2>
    <p class="section-sub">Our Recipes Collection.</p>

    <div class="row g-4">
      <?php if(count($recipes) > 0){ ?>
      <?php foreach($recipes as $rec){ ?>
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="recipe-card">
          <img src="
          <?php echo htmlspecialchars($rec->image); ?>" alt="<?php echo htmlspecialchars($rec->title); ?>">
          <div class="card-body">
            <h3 class="h5 mb-2"><?php echo htmlspecialchars($rec->title); ?></h3>
            <div class="meta-row d-flex gap-3 mb-2">
              <span><i class="bi bi-clock"></i><?php echo htmlspecialchars($rec->ptime + $rec->ctime); ?>m</span>
              <span><i class="bi bi-people"></i><?php echo htmlspecialchars($rec->serv); ?> servings</span>
            </div>
            <a href="#" class="btn btn-outline-secondary btn-sm view-recipe" data-id="<?php echo $rec->id; ?>">View recipe</a>
          </div>
        </div>
      </div>
      <?php } ?>
       <?php } ?>
    </div>
  </section>



  <!-- ===================== ADD RECIPE (form) ===================== -->
  <section id="add-recipe" class="mb-4">
    <h2 class="section-title">Add a recipe</h2>
    
    <div class="form-card">
       <form id="recipeForm">
        <div class="row g-3">
          <div class="col-md-8">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" placeholder="e.g. Beef and Vegetable Stir Fry">
          </div>
          <div class="col-md-4">
            <label for="servings" class="form-label">Servings</label>
            <input type="number" class="form-control" id="servings" placeholder="3">
          </div>

          <div class="col-12">
            <label for="desc" class="form-label">Description</label>
            <input type="text" class="form-control" id="desc" placeholder="One line about this dish">
          </div>

          <div class="col-12">
            <label for="inst" class="form-label">Instructions</label>
            <textarea class="form-control" id="inst" rows="4" placeholder="1. ...&#10;2. ...&#10;3. ..."></textarea>
          </div>

          <div class="col-md-4">
            <label for="ptime" class="form-label">Prep time (minutes)</label>
            <input type="number" class="form-control" id="ptime" placeholder="15">
          </div>
          <div class="col-md-4">
            <label for="ctime" class="form-label">Cook time (minutes)</label>
            <input type="number" class="form-control" id="ctime" placeholder="10">
          </div>
          <div class="col-md-4">
            <label for="image" class="form-label">Image URL</label>
            <input type="url" class="form-control" id="image" placeholder="http://localhost/.../stirfry.jpg">
          </div>
        </div>

       <div id="formFeedback" class="mt-4"></div>

        <button type="button" class="btn btn-hero" id="saveRecipeBtn">Save recipe</button>
      </form>
    </div>
  </section>

</main>

<footer class="py-4">
  <div class="container">
   &copy; 2026 FreshPlate. All rights reserved
  </div>
</footer>

<div class="modal fade" id="recipeModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="modalDesc" class="text-body-tertiary"></p>
        <div class="mb-3" id="modalTags"></div>
        <h6>Ingredients</h6>
        <ul id="modalIngredients" class="mb-3"></ul>
        <h6>Instructions</h6>
        <p id="modalInstructions" style="white-space:pre-line;"></p>
      </div>
    </div>
  </div>
</div>

<script src="assets/static/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.view-recipe').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    e.preventDefault();
    const id = btn.dataset.id;
    const res = await fetch(`http://localhost/recipe_planner/api/v1/recipes/detail.php?id=${id}`, {
      headers: { "Authorization": "Basic " + btoa("Abie:recipe@PLAN") }
    });
    const result = await res.json();
    const r = result.data;

    document.getElementById('modalTitle').textContent = r.title;
    document.getElementById('modalDesc').textContent = r.description;
    document.getElementById('modalTags').innerHTML = r.tags
      .map(t => `<span class="badge-tag">${t.name}</span>`).join('');
    document.getElementById('modalIngredients').innerHTML = r.ingredients
      .map(i => `<li>${i.name} — ${i.quantity} ${i.unit}</li>`).join('');
    document.getElementById('modalInstructions').textContent = r.instructions;

    new bootstrap.Modal(document.getElementById('recipeModal')).show();
  });
});

document.getElementById('saveRecipeBtn').addEventListener('click', async () => {
  const payload = {
    title: document.getElementById('title').value,
    desc:  document.getElementById('desc').value,
    inst:  document.getElementById('inst').value,
    ptime: document.getElementById('ptime').value,
    ctime: document.getElementById('ctime').value,
    serv:  document.getElementById('servings').value,
    image: document.getElementById('image').value,
  };

  const feedback = document.getElementById('formFeedback');
  feedback.innerHTML = '';

  const res = await fetch('http://localhost/recipe_planner/api/v1/recipes/create.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Basic ' + btoa('Abie:recipe@PLAN')
    },
    body: JSON.stringify(payload)
  });
  const result = await res.json();

  if (result.status === 'success') {
    feedback.innerHTML = `
      <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-check-circle"></i>
        <div>Recipe added successfully.</div>
      </div>`;
    document.getElementById('recipeForm').reset();
  } else {
    feedback.innerHTML = `
      <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-exclamation-circle"></i>
        <div>${result.message}</div>
      </div>`;
  }
});
</script>

</body>
</html>
