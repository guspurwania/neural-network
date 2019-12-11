<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Phpml\Classification\KNearestNeighbors;

use Phpml\Classification\MLPClassifier;
use Phpml\NeuralNetwork\ActivationFunction\PReLU;
use Phpml\NeuralNetwork\ActivationFunction\Sigmoid;
use Phpml\NeuralNetwork\Layer;
use Phpml\NeuralNetwork\Node\Neuron;

class ClassificationKNNController extends Controller
{

	public function index()
	{
		$samples = [[80, 75, 70, 89], [50, 40, 45, 60], [60, 55, 65, 68], [90, 85, 87, 89], [10, 15, 10, 15], [100, 90, 95, 90]];
		$targets = ['Pemimpin', 'Pecundang', 'Pecundang', 'Pemimpin', 'Pecundang', 'Pemimpin'];

		$classifier = new KNearestNeighbors();
		$classifier->train($samples, $targets);

		echo $classifier->predict([90, 70, 80, 100]);
	}

	public function mlp_index()
	{
		$layer1 = new Layer(4, Neuron::class, new PReLU);
		$layer2 = new Layer(2, Neuron::class, new Sigmoid);
		$mlp = new MLPClassifier(4, [$layer1, $layer2], ['Pemimpin', 'Pecundang']);

		$mlp->partialTrain(
		    $samples = [[80, 75, 70, 89], [50, 40, 45, 60], [60, 55, 65, 68], [90, 85, 87, 89], [10, 15, 10, 15], [100, 90, 95, 90]],
		    $targets = ['Pemimpin', 'Pecundang', 'Pecundang', 'Pemimpin', 'Pecundang', 'Pemimpin']
		);
		$mlp->partialTrain(
		    $samples = [[90, 89, 99, 100], [25, 35, 45, 55], [34, 45, 32, 21], [94, 85, 75, 77], [20, 30, 40, 50]],
		    $targets = ['Pemimpin', 'Pecundang', 'Pecundang', 'Pemimpin', 'Pecundang']
		);

		$mlp->setLearningRate(0.1);

		dd($mlp->predict([[20, 40, 30, 40], [90, 70, 80, 100]]));
	}

}