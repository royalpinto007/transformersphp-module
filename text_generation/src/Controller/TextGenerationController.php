<?php

namespace Drupal\text_generation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Codewithkyrian\Transformers\Pipelines\pipeline;

/**
 * Returns responses for Text Generation routes.
 */
class TextGenerationController extends ControllerBase {

  /**
   * Displays the text generation page.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request object.
   *
   * @return array
   *   A render array.
   */
  public function generateText(Request $request) {
    $prompt = $request->query->get('prompt', '');
    $generated_text = '';

    if (!empty($prompt)) {
      try {
        $pipeline = pipeline('text-generation', 'Xenova/gpt2');
        $result = $pipeline($prompt);
        $generated_text = $result[0]['generated_text'];
      } catch (\Exception $e) {
        $generated_text = 'Error generating text: ' . $e->getMessage();
      }
    }

    return [
      '#theme' => 'text_generation_page',
      '#prompt' => $prompt,
      '#generated_text' => $generated_text,
    ];
  }

}
