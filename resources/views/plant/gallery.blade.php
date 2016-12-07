<style>
    .mySlides {display:none}
    .demo {cursor:pointer}
</style>

<div class="w3-content" style="max-width:1000px">
    @foreach($pictures as $datetime => $photo)
        @if($index % 2 == 0)
        <div class="w3-display-container mySlides w3-center" >
            <img height="400px" class="w3-round-large w3-animate-left" src="{{ url($photo) }}">
            <div class="w3-display-bottomleft w3-large w3-container w3-padding-16 w3-black">
                {{ $datetime }}
            </div>
        </div>
        @else
        <div class="w3-display-container mySlides w3-center ">
            <img  height="400px" class="w3-round-large w3-animate-right" src="{{ url($photo) }}">
            <div class="w3-display-bottomleft w3-large w3-container w3-padding-16 w3-black">
                {{ $datetime }}
            </div>
        </div>
        @endif
    @endforeach

    <div class="w3-row-padding w3-section">
        <?php $index = 0; ?>
        @foreach($pictures as $photo)
            <div class="w3-col m4 s6 l3 w3-padding-bottom">
                <img class="demo w3-opacity w3-hover-opacity-off" src="{{ url($photo) }}" style="width:100%" onclick="currentDiv({{ ++$index }})">
            </div>
        @endforeach
    </div>
</div>

<script>
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function currentDiv(n) {
        showDivs(slideIndex = n);
    }
    carousel();
    function carousel(){
        plusDivs(1);
        setTimeout(carousel, 6000);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demo");
        if (n > x.length) {slideIndex = 1}
        if (n < 1) {slideIndex = x.length}
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" w3-opacity-off", "");
        }
        x[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " w3-opacity-off";
    }
</script>