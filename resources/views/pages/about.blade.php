@extends('layouts.app')
@section('content')
<div class="team-container">
    <h2>About Us</h2>
    <div class="about-text">
        <p><b>TechNet Chronicles</b> stands as a dynamic collaborative platform for technology enthusiasts, developers, and
            aficionados to share and engage with the latest tech news. Our mission centers around democratizing
            information flow and fostering a community that values knowledge and collaboration in the tech world.</p>
    </div>
    <div class="team-name">
        <h3>The team:</h3>
    </div>
    <div class="members">

        <div class="member">
            <div class="member-image">
                <img src="{{ asset('img/202108833.jpg') }}" alt="202108833 Creator" class="Creator-image">
            </div>
            <div class="member-name">
                <h4>João Pedro Sá Torres Neiva Passos</h4>
                <p> E-mail: up202108833@up.pt</p>
            </div>
        </div>
        <div class="member">
            <div class="member-image">
                <img src="{{ asset('img/202005334.jpg') }}" alt="202005334 Creator" class="Creator-image">
            </div>

            <div class="member-name">
                <h4>Rúben Tiago Oliveira Silva</h4>
                <p> E-mail: up202005334@up.pt</p>
            </div>
        </div>
        <div class="member">
            <div class="member-image">

                <img src="{{ asset('img/202103341.jpg') }}" alt="202103341 Creator" class="Creator-image">
            </div>

            <div class="member-name">
                <h4>Diogo Fernando Pinheiro Sousa</h4>
                <p> E-mail: up202103341@up.pt</p>
            </div>
        </div>
        <div class="member">
            <div class="member-image">
                <img src="{{ asset('img/202004159.jpg') }}" alt="202004159 Creator" class="Creator-image">
            </div>

            <div class="member-name">
                <h4>André Gabriel Correia Vieira</h4>
                <p> E-mail: up202004159@up.pt </p>
            </div>
        </div>
    </div>
</div>
@endsection