<?php
use Livewire\Component;
use App\Services\TopPerformerService; 

new class extends Component
{
    public string $message = '';
    public bool $readyToLoad = false; // Add a flag to delay calculation

    

    public function mount(TopPerformerService $service)
    {
    
        // $top = $service->getTopPerformer();

        // if ($top && isset($top['name'])) {
        //     $this->message = "🏆 Top Performer: {$top['name']} | "
        //         . "Disbursal: ₹" . number_format($top['disbursal']) . " | "
        //         . "Achievement: {$top['percentage']}%";
        // } else {
        //     $this->message = "🏆 Calculating Top Performers...";
        // }

        // $performers = $service->getTopPerformer();
        // if (!empty($performers)) {
        //     $compiledStrings = [];

            
        //     // Loop through each record and format its marquee message block
        //     foreach ($performers as $index => $top) {
        //         $rank = $index + 1;
        //         $compiledStrings[] = "🏆 Rank #{$rank}: {$top['name']} (Disbursal: ₹" . number_format($top['disbursal']) . " | Achievement: {$top['percentage']}%)";
        //     }
            
        //     // Join all performers together with spaces and a sleek divider symbol
        //     $this->message = implode("       •       ", $compiledStrings);
        // } else {
        //     $this->message = "🏆 Calculating Top Performers...";
        // }

         $this->loadPerformers($service);
    }


        public function loadPerformers(TopPerformerService $service)
    {
      
         $service = app(TopPerformerService::class);
            // dd($service->getTopPerformer());

        $performers = $service->getTopPerformer();

        if (empty($performers)) {
            $this->message = '🏆 No Top Performers Found';
            return;
        }

        $messages = [];

        foreach ($performers as $index => $top) {

            $rank = match ($index) {
                0 => '🥇',
                1 => '🥈',
                2 => '🥉',
                default => '🏅',
            };

            $messages[] =
                "{$rank} {$top['name']} | ₹"
                . number_format($top['disbursal'])
                . " | {$top['percentage']}%";
        }

        $this->message = implode('     •     ', $messages);
    }



    // public function render(){
      
    //   return <<<'HTML'
    //         <div class="fi-top-marquee-wrapper">
    //             <div class="ticker-container">
    //                 <div class="marquee-text">
    //                     <span>{{ $message }}</span>
    //                     <span class="ml-24" aria-hidden="true">{{ $message }}</span>
    //                 </div>
    //             </div>
    //         </div>

            // <style>
            // .fi-top-marquee-wrapper {
            //     position: absolute;
            //     left: 260px;       /* Start after Filament logo/sidebar area */
            //     right: 160px;      /* End before profile area */
            //     top: 50%;
            //     transform: translateY(-50%);
            //     overflow: hidden;
            //     z-index: 10;
            // }

            // .ticker-container {
            //     width: 100%;
            //     overflow: hidden;
            // }

            // .marquee-text {
            //     display: inline-flex;
            //     white-space: nowrap;
            //     animation: marquee 25s linear infinite;
            //     font-weight: 900;
            //     font-size: 1rem;
            //     color: #ae2012;
            // }

            // .marquee-text:hover {
            //     animation-play-state: paused;
            // }

            // @keyframes marquee {
            //     from {
            //         transform: translateX(100%);
            //     }
            //     to {
            //         transform: translateX(-100%);
            //     }
            // }
            // </style>
    //         HTML;
                
    //         }


       public function render()
    {
        return <<<'HTML'
            <div wire:poll.60s="loadPerformers" class="fi-top-marquee-wrapper">
                <div class="ticker-container">
                    <div class="marquee-text">
                        <span>{{ $message }}</span>
                        <span class="ml-24">{{ $message }}</span>
                    </div>
                </div>
            </div>

            <!-- Your CSS -->


                        <style>
            .fi-top-marquee-wrapper {
                position: absolute;
                left: 260px;       /* Start after Filament logo/sidebar area */
                right: 160px;      /* End before profile area */
                top: 50%;
                transform: translateY(-50%);
                overflow: hidden;
                z-index: 10;
            }

            .ticker-container {
                width: 100%;
                overflow: hidden;
            }

            .marquee-text {
                display: inline-flex;
                white-space: nowrap;
                animation: marquee 25s linear infinite;
                font-weight: 900;
                font-size: 1rem;
                color: #ae2012;
            }

            .marquee-text:hover {
                animation-play-state: paused;
            }

            @keyframes marquee {
                from {
                    transform: translateX(100%);
                }
                to {
                    transform: translateX(-100%);
                }
            }
            </style>

             
        HTML;
    }



};

?>
