/*
Copyright 2017 Ziadin Givan

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

https://github.com/givanz/Vvvebjs
*/

Vvveb.SectionsGroup['Bootstrap'] =
    [
        "bootstrap4/signin-split",
        "bootstrap4/image-gallery",
        "bootstrap4/video-header",
        "bootstrap4/about-team",
        "bootstrap4/portfolio-one-column",
        "bootstrap4/portfolio-two-column",
        "bootstrap4/portfolio-three-column",
        "bootstrap4/portfolio-four-column",
    ];

Vvveb.Sections.add("bootstrap4/image-gallery", {
    name: "Image gallery",
    image: "https://assets.startbootstrap.com/img/screenshots/snippets/thumbnail-gallery.jpg",
    dragHtml: '<img src="' + Vvveb.baseUrl + 'icons/product.png">',
    html: `
<section data-name="image-gallery">
    <div class="container">

        <h1 class="font-weight-light text-center text-lg-left">Thumbnail Gallery</h1>

        <hr class="mt-2 mb-5">

        <div class="row text-center text-lg-left">

            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/ff5c5c/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/5c6dff/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/ffc75c/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/5cffa3/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/ffa15c/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/5c85ff/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/d15cff/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/5cff6f/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/ff5c9d/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/5cffe3/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/ff8e5c/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail" src="https://dummyimage.com/400x300/5c5cff/fff" alt=""
                         width="100%">
                </a>
            </div>
        </div>

    </div>
</section>`,
});

Vvveb.Sections.add("bootstrap4/video-header", {
    name: "Video Header",
    dragHtml: '<img src="' + Vvveb.baseUrl + 'icons/image.svg">',
    image: "https://assets.startbootstrap.com/img/screenshots/snippets/video-header.jpg",
    html: `
<header class="video" data-name="header-video">
    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="https://github.com/user-attachments/assets/6893853e-fc76-497d-83d2-d87c7395be57" type="video/mp4">
    </video>
    <div class="container h-100">
        <div class="d-flex h-100 text-center align-items-center">
            <div class="w-100 text-white">
                <h1 class="display-3">Video Header</h1>
                <p class="lead mb-0">With HTML5 Video and Bootstrap 4</p>
            </div>
        </div>
    </div>


    <div class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <p>The HTML5 video element uses an mp4 video as a source. Change the source video to add in your own
                        background! The header text is vertically centered using flex utilities that are build into
                        Bootstrap 4.</p>
                </div>
            </div>
        </div>
    </div>
    <style>
        header.video {
            position: relative;
            background-color: black;
            height: 75vh;
            min-height: 25rem;
            width: 100%;
            overflow: hidden;
        }
        header.video video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 0;
            -ms-transform: translateX(-50%) translateY(-50%);
            -moz-transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
        }
        header.video .container {
            position: relative;
            z-index: 2;
        }
        header.video .overlay {
            /*position: absolute;*/
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: black;
            opacity: 0.5;
            z-index: 1;
        }
        @media (pointer: coarse) and (hover: none) {
            header {
                background: url('https://dummyimage.com/1600x900/c5d647/fff') black no-repeat center center scroll;
            }
            header video {
                display: none;
            }
        }
    </style>
</header>`,
});

Vvveb.Sections.add("bootstrap4/portfolio-one-column", {
    name: "One Column Portfolio Layout",
    dragHtml: '<img src="' + Vvveb.baseUrl + 'icons/image.svg">',
    image: "https://assets.startbootstrap.com/img/screenshots/snippets/portfolio-one-column.jpg",
    html: `
<section data-name="portfolion-one-column">
    <div class="container">

        <h1 class="my-4">Page Heading</h1>

        <div class="row">
            <div class="col-md-7">
                <a href="#">
                    <img class="img-fluid rounded mb-3 mb-md-0" src="https://dummyimage.com/700x300/ff5c5c/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-md-5">
                <h3>Project One</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita
                    laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos
                    perspiciatis atque eveniet unde.</p>
                <a class="btn btn-primary" href="#">View Project</a>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-7">
                <a href="#">
                    <img class="img-fluid rounded mb-3 mb-md-0" src="https://dummyimage.com/700x300/5c6dff/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-md-5">
                <h3>Project Two</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, odit velit cumque vero doloremque
                    repellendus distinctio maiores rem expedita a nam vitae modi quidem similique ducimus! Velit, esse
                    totam tempore.</p>
                <a class="btn btn-primary" href="#">View Project</a>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-7">
                <a href="#">
                    <img class="img-fluid rounded mb-3 mb-md-0" src="https://dummyimage.com/700x300/ffc75c/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-md-5">
                <h3>Project Three</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, temporibus, dolores, at, praesentium
                    ut unde repudiandae voluptatum sit ab debitis suscipit fugiat natus velit excepturi amet commodi
                    deleniti alias possimus!</p>
                <a class="btn btn-primary" href="#">View Project</a>
            </div>
        </div>

        <hr>

        <div class="row">

            <div class="col-md-7">
                <a href="#">
                    <img class="img-fluid rounded mb-3 mb-md-0" src="https://dummyimage.com/700x300/5cffa3/fff" alt=""
                         width="100%">
                </a>
            </div>
            <div class="col-md-5">
                <h3>Project Four</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo, quidem, consectetur, officia rem
                    officiis illum aliquam perspiciatis aspernatur quod modi hic nemo qui soluta aut eius fugit quam in
                    suscipit?</p>
                <a class="btn btn-primary" href="#">View Project</a>
            </div>
        </div>

        <hr>

        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>

    </div>
</section>`,
});

Vvveb.Sections.add("bootstrap4/portfolio-two-column", {
    name: "Two Column Portfolio Layout",
    dragHtml: '<img src="' + Vvveb.baseUrl + 'icons/image.svg">',
    image: "https://assets.startbootstrap.com/img/screenshots/snippets/portfolio-one-column.jpg",
    html: `
<section data-name="portfolio-two-column">
    <div class="container">

        <h1 class="my-4">Page Heading</h1>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ff5c5c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project One</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5c6dff/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Two</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit aliquam
                            aperiam nulla perferendis dolor nobis numquam, rem expedita, aliquid optio, alias illum
                            eaque. Non magni, voluptates quae, necessitatibus unde temporibus.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ffc75c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Three</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5cffa3/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Four</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit aliquam
                            aperiam nulla perferendis dolor nobis numquam, rem expedita, aliquid optio, alias illum
                            eaque. Non magni, voluptates quae, necessitatibus unde temporibus.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ffa15c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Five</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/d15cff/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Six</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit aliquam
                            aperiam nulla perferendis dolor nobis numquam, rem expedita, aliquid optio, alias illum
                            eaque. Non magni, voluptates quae, necessitatibus unde temporibus.</p>
                    </div>
                </div>
            </div>
        </div>

        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>

    </div>
</section>`,
});

Vvveb.Sections.add("bootstrap4/portfolio-three-column", {
    name: "Three Column Portfolio Layout",
    dragHtml: '<img src="' + Vvveb.baseUrl + 'icons/image.svg">',
    image: "https://assets.startbootstrap.com/img/screenshots/snippets/portfolio-three-column.jpg",
    html: `
<section data-name="portfolio-three-column">
    <div class="container">

        <h1 class="my-4">Page Heading</h1>

        <div class="row">
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ff5c5c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project One</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam
                            aspernatur eum quasi sapiente nesciunt? Voluptatibus sit, repellat sequi itaque deserunt,
                            dolores in, nesciunt, illum tempora ex quae? Nihil, dolorem!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5c6dff/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Two</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ffc75c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Three</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos quisquam,
                            error quod sed cumque, odio distinctio velit nostrum temporibus necessitatibus et facere
                            atque iure perspiciatis mollitia recusandae vero vel quam!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5cffa3/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Four</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ffa15c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Five</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5cffe3/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Six</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque earum
                            nostrum suscipit ducimus nihil provident, perferendis rem illo, voluptate atque, sit eius in
                            voluptates, nemo repellat fugiat excepturi! Nemo, esse.</p>
                    </div>
                </div>
            </div>
        </div>

        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>

    </div>
</section>`,
});

Vvveb.Sections.add("bootstrap4/portfolio-four-column", {
    name: "Four Column Portfolio Layout",
    dragHtml: '<img src="' + Vvveb.baseUrl + 'icons/image.svg">',
    image: "https://assets.startbootstrap.com/img/screenshots/snippets/portfolio-four-column.jpg",
    html: `
<section data-name="portfolio-four-column">
    <div class="container">

        <h1 class="my-4">Page Heading</h1>

        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ff5c5c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project One</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam
                            aspernatur eum quasi sapiente nesciunt? Voluptatibus sit, repellat sequi itaque deserunt,
                            dolores in, nesciunt, illum tempora ex quae? Nihil, dolorem!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5c6dff/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Two</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ffc75c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Three</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos quisquam,
                            error quod sed cumque, odio distinctio velit nostrum temporibus necessitatibus et facere
                            atque iure perspiciatis mollitia recusandae vero vel quam!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5cffa3/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Four</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ffa15c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Five</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/d15cff/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Six</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque earum
                            nostrum suscipit ducimus nihil provident, perferendis rem illo, voluptate atque, sit eius in
                            voluptates, nemo repellat fugiat excepturi! Nemo, esse.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/5cffe3/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Seven</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra
                            euismod odio, gravida pellentesque urna varius vitae.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top" src="https://dummyimage.com/700x400/ff8e5c/fff" alt=""
                                     width="100%"></a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="#">Project Eight</a>
                        </h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius adipisci
                            dicta dignissimos neque animi ea, veritatis, provident hic consequatur ut esse! Commodi ea
                            consequatur accusantium, beatae qui deserunt tenetur ipsa.</p>
                    </div>
                </div>
            </div>
        </div>

        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>

    </div>
</section>`,
});
