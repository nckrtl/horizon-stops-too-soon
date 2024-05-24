<div wire:poll class="max-w-2xl w-full mx-auto">
    <div class="flex w-full items-center justify-between bg-gray-100 p-2 px-4 rounded-xl mt-4">
        <div>
            @if($canShowHorizonControls)
                <div wire:click="startHorizon" class="cursor-pointer bg-green-500 text-white rounded-lg py-2 px-4" >
                    Start horizon process
                </div>
            @endif
        </div>
        <div class=" flex flex-col items-center">
            <div>Horizon status:</div>
            <div class="text-2xl">{{ $horizonStatus }}</div>
        </div>
        <div>
            @if($canShowHorizonControls)
                <div wire:click="stopHorizon" class="cursor-pointer bg-red-500 text-white rounded-lg py-2 px-4" >
                    Terminate horizon
                </div>
            @endif
        </div>
    </div>

    <div class="bg-gray-100 p-4 flex justify-between mt-4 rounded-xl">
        <div class="text-2xl">
            Jobs
        </div>
        <div class="flex items-center space-x-4">
            <div wire:click="addJob" class="cursor-pointer bg-gray-900 px-4 py-2 rounded-lg text-white" >
                Add job
            </div>
            <div wire:click="clearAllJobs" class="cursor-pointer px-4 py-2 bg-red-500 text-white rounded-lg" >
                Clear all jobs
            </div>
        </div>
    </div>

    <div class="flex flex-col space-y-4 mt-4">
    @foreach($jobModels as $job)
        <div class="flex flex-col w-full">
            <div class="w-full flex justify-between">
                 <span>Job {{ $job['id'] }}</span>
                 <span> {{ ceil(($job['progress']/$job['total'])*100) }}% </span>
            </div>

            <div class="relative w-full h-2 rounded-full overflow-hidden mt-1">
                <div class="absolute w-full top-0 left-0 h-full bg-gray-100"></div>
                <div class="absolute top-0 left-0 h-full rounded-full bg-blue-500" style="width: {{ $job['progress']/$job['total']*100 }}%; transition: width 0.5s;"></div>
            </div>
        </div>
    @endforeach
    </div>
</div>


