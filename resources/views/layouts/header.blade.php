<style>
  .navbar-nav {
    background: #03A9F4 !important;
    padding-left: 10px;
  }
</style>

<nav class="navbar navbar-expand-lg" style="background-color: #03A9F4 !important">
  <a class="navbar-brand" href="#">
    <img src="{{asset('images/m-bg.png')}}" alt="logo" height="50">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent1">
    <ul class="navbar-nav me-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/">Home <span class="visually-hidden">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('projects-view')}}">Projects</a>
      </li>
    </ul>
  </div>
</nav>