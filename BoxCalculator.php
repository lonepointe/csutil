<?php
class BoxCalculator {
    private $machineCycleTime; // seconds per cycle
    private $cavities; // number of cavities in the tool
    private $timePeriodHours; // time period in hours
    private $partsPerBox; // number of parts that fit into one box

    public function __construct($machineCycleTime, $cavities, $timePeriodHours, $partsPerBox) {
        $this->machineCycleTime = $machineCycleTime;
        $this->cavities = $cavities;
        $this->timePeriodHours = $timePeriodHours;
        $this->partsPerBox = $partsPerBox;
    }

    public function calculate() {
        // Calculate total production time in seconds
        $totalTimeSeconds = $this->timePeriodHours * 3600;

        // Calculate the total number of cycles
        $totalCycles = floor($totalTimeSeconds / $this->machineCycleTime);

        // Calculate the total number of parts produced
        $totalParts = $totalCycles * $this->cavities;

        // Calculate the number of boxes required
        $totalBoxes = ceil($totalParts / $this->partsPerBox);

        // Calculate components per hour
        $numPerHour = ceil((floor(3600 / $this->machineCycleTime) * $this->cavities) / $this->partsPerBox);


        //$numPerHour=ceil((floor(3600 / $this->machineCycleTime) * $this->cavities) / $this->partsPerBox)



        return [
            'Machine Cycle Time' => $this->machineCycleTime,
            'Cavities' => $this->cavities,
            'Time Period (hours)' => $this->timePeriodHours,
            'Parts Per Box' => $this->partsPerBox,
            'Total Time (seconds)' => $totalTimeSeconds,
            'Total Cycles' => $totalCycles,
            'Total Parts Produced' => $totalParts,
            'Total Boxes Required' => $totalBoxes,
            'Boxes per Hour' => $numPerHour

        ];
    }
}

