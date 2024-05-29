    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="app-nav">
                <ul class="navbar-nav mb-2 mb-lg-0"><?php
                    $cats = getElement('categories', 'ID');
                    foreach($cats as $cat){
                        echo '<li class="nav-item">';
                            echo '<a class="nav-link" aria-current="page" href="categories.php?catid='.$cat['ID'].'&catName='.str_replace('-',' ',$cat['name']).'">'
                                        .str_replace('-',' ',$cat["name"]).
                                 '</a>';
                        echo '</li>';
                    }?>
                </ul>
            </div>
        </div>
    </nav>
