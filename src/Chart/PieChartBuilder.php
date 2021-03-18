<?php

namespace CViniciusSDias\AnaliseSentimento\Chart;

use Amenadiel\JpGraph\Graph\Legend;
use Amenadiel\JpGraph\Graph\PieGraph;
use Amenadiel\JpGraph\Plot\DisplayValue;
use Amenadiel\JpGraph\Plot\PiePlot3D;
use Amenadiel\JpGraph\Text\Text;

class PieChartBuilder
{
    private PieGraph $graph;
    private PiePlot3D $plot;

    private function __construct(int $width = 300, int $height = 200)
    {
        $this->graph = new PieGraph($width, $height);
        /** @var Legend $this->graph->legend */
        $this->graph->legend->font_size = 14;
        $this->graph->legend->SetPos(0.5, 0.99, 'center', 'bottom');
    }

    public static function createWithSize(int $width, int $height): self
    {
        return new self($width, $height);
    }

    public static function create(): self
    {
        return new self();
    }

    /** @psalm-suppress UndefinedConstant */
    public function withTitle(string $title): self
    {
        /** @var Text $this->graph->title */
        $this->graph->title->Set($title);
        /** @var Text $this->graph->title */
        $this->graph->title->SetFont(FF_DEFAULT, FS_NORMAL, 18);
        return $this;
    }

    /** @psalm-suppress UndefinedConstant */
    public function withSubTitle(string $subTitle): self
    {
        /** @var Text $this->graph->subtitle */
        $this->graph->subtitle->Set($subTitle);
        /** @var Text $this->graph->subtitle */
        $this->graph->subtitle->SetFont(FF_DEFAULT, FS_NORMAL, 14);
        return $this;
    }

    /** @psalm-suppress UndefinedConstant */
    public function withValues(array $data): self
    {
        $this->plot = new PiePlot3D($data);
        $this->plot->SetAngle(60);
        /** @var DisplayValue $this->plot->value */
        $this->plot->value->SetFont(FF_DEFAULT, FS_NORMAL, 12);
        $this->plot->value->SetColor('black');

        return $this;
    }

    /**
     * @param array<string, string> $legends
     * @return $this
     */
    public function withLegends(array $legends): self
    {
        $this->plot->SetLegends(array_keys($legends));
        $this->plot->SetSliceColors(array_values($legends));

        return $this;
    }

    public function drawToImageFile(string $fileName): void
    {
        $this->graph->Add($this->plot);
        $this->graph->Stroke($fileName);
    }
}
