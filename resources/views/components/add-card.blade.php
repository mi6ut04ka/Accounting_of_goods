@props(['link'=>''])
<div class="card mb-4 m-3" style="width: 18rem; min-height: 260px;">
    <div class="card-body d-flex justify-content-center align-items-center" style="height: 100%;">
        <a href="{{$link}}" class="plus-link btn btn-outline-secondary rounded-circle d-flex justify-content-center align-items-center"
           style="width: 120px; height: 120px; text-decoration: none; position: relative;">
        </a>
    </div>
</div>

<style>
    .plus-link {
        position: relative;
        width: 120px;
        height: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }

    .plus-link::before,
    .plus-link::after {
        content: '';
        position: absolute;
        background-color: #718096;
    }

    .plus-link::before {
        width: 5px;
        height: 50px;
    }

    .plus-link::after {
        width: 50px;
        height: 5px;
    }

    .plus-link::before {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .plus-link::after {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
