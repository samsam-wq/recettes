<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Recipe Collection</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/styleIndex.css" />
</head>
<body>

<div class="recipe-homepage">
  <!-- HEADER -->
  <header class="header">
    <div class="header-content">
      <h1 class="site-title">Nos recettes</h1>
    </div>
  </header>

  <!-- FILTER BAR -->
  <div class="filter-bar">
    <!-- Category -->
    <div class="filter-group">
      <label class="filter-label">Categories</label>
      <select class="filter-select">
        <option value="all">Toutes les categories</option>
        <option value="appetizer">Apéros</option>
        <option value="main">Plats principaux</option>
        <option value="dessert">Dessert</option>
        <option value="vegetarian">Vegetarian</option>
        <option value="seafood">Seafood</option>
      </select>
    </div>

    <!-- Favorites -->
    <div class="filter-group">
      <label class="filter-label">Favorites</label>
      <label class="favorites-toggle">
        <input type="checkbox" id="favoritesFilter" />
        <span class="checkbox-custom"></span>
        Favorites Only
      </label>
    </div>

    <!-- Sort -->
    <div class="filter-group">
      <label class="filter-label">Sort By</label>
      <select class="filter-select" id="sortFilter">
        <option value="default">Default</option>
        <option value="rating">Highest Rating</option>
        <option value="duration-asc">Shortest Duration</option>
        <option value="duration-desc">Longest Duration</option>
      </select>
    </div>

    <!-- Random -->
    <div class="random-recipe-wrapper">
    
      <form method="get" action="/recipes" class="filters-form">

        <details class="filters-dropdown">
          <summary class="filters-toggle">Filters</summary>

          <div class="filters-content">
            <fieldset>
              <legend>Categories</legend>

              <label>
                <input type="checkbox" name="category[]" value="dessert">
                Dessert
              </label>

              <label>
                <input type="checkbox" name="category[]" value="main">
                Main
              </label>

              <label>
                <input type="checkbox" name="category[]" value="vegetarian">
                Vegetarian
              </label>
            </fieldset>

            <fieldset>
              <legend>Other</legend>

              <label>
                <input type="checkbox" name="favorites" value="1">
                Favorites only
              </label>
            </fieldset>

            <button type="submit" class="apply-filters-btn">
              Search
            </button>
          </div>
        </details>

      </form>

      <button class="random-recipe-btn" id="randomRecipeBtn">
        Random Recipe
      </button>

    </div>
  </div>
</div>

  <!-- MAIN -->
  <main class="main-content">
    <div class="recipes-grid">
      <!-- Recipe cards ici (inchangées, tu peux les coller telles quelles) -->
    </div>
  </main>
</div>

</body>
</html>
