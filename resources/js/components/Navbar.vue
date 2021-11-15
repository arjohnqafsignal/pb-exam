<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <router-link :to="{ name: 'list' }"
                         class="navbar-brand">Penbrothers Technical Exam | Arjohn Quijano</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link active-class="active"
                                     exact :to="{ name: 'list' }"
                                     class="nav-link">List</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active"
                                     exact :to="{ name: 'create' }"
                                     class="nav-link">Order Form</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
