<?php

namespace Drupal\learning\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Kint;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\InsertCommand;
use Drupal\Core\Ajax\OpenDialogCommand;

/**
 * Class AjaxForm.
 */
class AjaxForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form';
  }

  /**
   * Helper method so we can have consistent dialog options.
   *
   * @return string[]
   *   An array of jQuery UI elements to pass on to our dialog form.
   */
  protected static function getDataDialogOptions() {
    return [
      'width' => '50%',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $nojs = NULL) {
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date Field'),
    ];
    $form['date_list'] = [
      '#type' => 'datelist',
      '#title' => $this->t('Date List'),
    ];
    $form['date_time'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date Titme'),
    ];
    $form['extra_actions'] = [
      '#type' => 'dropbutton',
      '#attributes' => ['style' => ['width' => '100px']],
      '#links' => [
        'simple_form' => [
          'title' => $this->t('Simple Form'),
          'url' => Url::fromUri('entity:node/16'),
        ],
        'demo' => [
          'title' => $this->t('Build Demo'),
          'url' => Url::fromUri('entity:node/16'),
        ],
      ],
    ];
    $form['html_tag'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('The content area color has been changed to'),
      '#attributes' => [
        'style' => 'background-color: red',
      ],
    ];
    $form['example_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Example select field'),
      '#options' => [
        '1' => $this->t('One'),
        '2' => $this->t('Two'),
        '3' => $this->t('Three'),
        '4' => $this->t('From New York to Ger-ma-ny!'),
      ],
      '#ajax' => [
        'callback' => '::myExampleAjaxCallback',
        'disable-refocus' => TRUE,
        'event' => 'change',
        'wrapper' => 'edit-output',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ]
    ];

    // Create a textbox that will be updated
    // when the user selects an item from the select box above.
    $form['output'] = [
      '#type' => 'textfield',
      '#size' => '60',
      '#disabled' => TRUE,
      '#value' => 'Hello, Drupal!!1',
      '#attributes' => [
        'class' => ['output_text']
      ],
      '#prefix' => '<div id="edit-output">',
      '#suffix' => '</div>',
    ];

    $form['drupal_modal_command'] = [
      '#type' => 'item',
      '#markup' => 'Drupal Model dialog',
      '#prefix' => '<div class="drupal_modal_command" id="drupal-dialog">',
      '#suffix' => '</div>',
    ];

    $form['ajax_append_command'] = [
      '#type' => 'item',
      '#markup' => 'Ajax Custom Template',
      '#prefix' => '<div class="ajax_append_command_cls" id="ajax_append_command">',
      '#suffix' => '</div>',
    ];

    $options = [
      '_none' => 'SELECT',
      'India' => $this->t('India'),
      'Australia' => $this->t('Australia'),
    ];
    $form['country_list'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#description' => $this->t('Select Country'),
      '#options' => $options,
      '#size' => 1,
      '#ajax' => [
        'callback' => '::ajaxCustomTemplate',
        'disable-refocus' => TRUE,
        'event' => 'change',
        'wrapper' => 'ajax_custom_template',
        'effect' => 'slide',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t("Loading..."),
          'interval' => 2,
        ],
      ],
    ];
    $form['ajax_custom_template_1'] = [
      '#type' => 'item',
      '#markup' => 'Ajax Custom Template',
      '#prefix' => '<div class="ajax_custom_template_1" id="ajax_custom_template">',
      '#suffix' => '</div>',
    ];
    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country List'),
      '#description' => $this->t('Select Country'),
      '#options' => $options,
      '#size' => 1,
    ];
    $form['country_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'country_wrapper'],
    ];
    $form['state'] = [
      '#type' => 'textfield',
      '#title' => $this->t('State'),
      '#description' => $this->t('State name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => '',
      //'#weight' => '4',
      '#prefix' => '<div id="state_wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
        //'callback' => [$this, 'myAjaxCallback'], //alternative notation
        'disable-refocus' => TRUE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'blur',
        'wrapper' => 'state_wrapper_list', // This element is updated with this AJAX callback.
        //'effect' => 'slide',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t("Loading..."),
          'interval' => 2,
        ],
      ],
    ];

    $form['state_list'] = [
      '#type' => 'select',
      '#title' => $this->t('State List'),
      '#description' => $this->t('State name'),
      '#options' => ['UP' => 'Uttar Pradesh', 'BIHAR' => 'Bihar'],
      '#prefix' => '<div id="state_wrapper_list">',
      '#suffix' => '</div>',
    ];

    $form['markup_wrapper_container'] = [
      '#type' => 'container',
      '#markup' => '',
      '#attributes' => ['id' => 'markup_wrapper'],
    ];
    $form['country_2'] = [
      '#type' => 'select',
      '#title' => $this->t('Country 2'),
      '#description' => $this->t('Select Country (Show debug)'),
      '#options' => $options,
      '#size' => 1,
      '#ajax' => [
        'callback' => '::myAjaxDialogCallback',
        //'callback' => [$this, 'myAjaxDialogCallback'],
        'disable-refocus' => FALSE,
        'event' => 'change',
        'wrapper' => 'markup_wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => "Please wait...",
          'interval' => 0,
        ],
      ],
    ];
    
    $form['custom_javascript_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'custom_javascript_output'],
    ];
    $form['debug_container'] = [
      '#type' => 'container',
      '#markup' => 'Debug container',
      '#attributes' => ['id' => 'debug_container_wrapper'],
    ];
    $form['ajax_link'] = [
      '#type' => 'link',
      '#title' => 'Ajax Link Model Dialog Example',
      '#attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => '{"width":600}',
        #'data-dialog-options' => json_encode(static::getDataDialogOptions()),
        'id' => 'ajax-example-modal-link',
        #'data-dialog-renderer' => 'off_canvas',
      ],
      '#url' => Url::fromUri('entity:node/1'),
    ];
    $form['model_dialog'] = [
      '#type' => 'link',
      '#title' => 'Ajax Link Example',
      '#attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(static::getDataDialogOptions()),
        // Add this id so that we can test this form.
        'id' => 'ajax-example-modal-link',
      ],
      '#url' => Url::fromRoute('learning.ajax_link_callback', ['nojs' => 'nojs']),
    ];
    // We provide a DIV that AJAX can append some text into.
    $form['ajax_link']['destination'] = [
      '#type' => 'container',
      '#attributes' => ['id' => ['ajax-example-destination-div']],
    ];
    $form['loader'] = [
      '#type' => 'item',
      '#markup' => 'Loader text',
      '#prefix' => '<div class="loader_container hidden" id="loader_container">Hello-',
      '#suffix' => '</div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#attributes' => ['class' => ['use-ajax-submit']],
      '#value' => $this->t('Submit'),
    ];
    
    if ($nojs == 'ajax') {
      $this->messenger()->addMessage("Model wondow open");
      $form['status_messages'] = [
        '#type' => 'container',
        '#markup' => 'Hello',
        '#weight' => -999,
      ];
      $form['status_messages'] = [
        '#type' => 'status_messages',
        '#weight' => -999,
      ];
    }
    if ($nojs == 'nojs') {
      unset($form['actions']['submit']['#ajax']);
    }
    return $form;
  }

  // Get the value from example select field and fill
  // the textbox with the selected text.
  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
    // Prepare our textfield. check if the example select field has a selected option.
    if ($selectedValue = $form_state->getValue('state')) {
      // Get the text of the selected option.
      #$selectedText = $form['country']['#options'][$selectedValue];
      // Place the text of the selected option in our textfield.
      if ($selectedValue == '_none') {
        $selectedValue = '';
      }
      $form['state_list']['#title'] = 'Ajax State';
      $form['state_list']['#options'] = ['UP' => 'Utter Pradesh', 'DELHI' => 'Delhi'];
    }
    // Return the prepared textfield.
    return $form['state_list'];
  }

  /**
   * An Ajax callback.
   */
  public function myAjaxDialogCallback(array &$form, FormStateInterface $form_state) {
    $selectedValue = $form_state->getValue('country_2');
    $elem = [
      '#type' => 'textfield',
      '#size' => '60',
      '#disabled' => TRUE,
      '#value' => "I am a Ajax textfield: $selectedValue!",
      '#attributes' => [
        'id' => ['state_wrapper'],
      ],
    ];
    $renderer = \Drupal::service('renderer');
    // Replace whole element.
    $renderedField = $renderer->render($elem);
    // Replace field attributes only.
    $form['state']['#title'] = 'Ajax Replace';
    $form['state']['#value'] = $selectedValue;

    //$renderedField = $renderer->render($form['state']);
    $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
    // Prepare the text for our dialogbox.
    $dialogText['#markup'] = "You selected: $selectedValue";
    // return [
    //   '#markup' => '<div id="markup_wrapper">Select Country - ' . $selectedValue . '</div>',
    // ];

    // If we want to execute AJAX commands our callback needs to return
    // an AjaxResponse object. let's create it and add our commands.
    $response = new AjaxResponse();
    // Issue a command that replaces the element #edit-output
    // with the rendered markup of the field created above.
    $response->addCommand(new ReplaceCommand('#state_wrapper', $renderedField));
    // Custom javascript code.
    $response->addCommand(new InvokeCommand(NULL, 'myAjaxCallback', ['Ajax callback value']));
    // Show the dialog box.
    //$response->addCommand(new OpenModalDialogCommand('My title', $dialogText, ['width' => '300']));
    $debugOut = @Kint::dump($response);
    $response->addCommand(new ReplaceCommand('#debug_container_wrapper', '<div id="debug_container_wrapper">' . $debugOut . '</div>'));
    // Finally return the AjaxResponse object.
    return $response;
  }

  public function ajaxCustomTemplate(array &$form, FormStateInterface $form_state) {
    $data = ['name' => 'Jeet', 'email' => 'jeet.lal1@tothenew.com'];
    $data['country'] = $form_state->getValue('country_list');
    $build = [
      '#theme' => 'ajax_custom_template',
      '#page_params' => [$data],
      '#prefix' => '<div class="ajax_custom_template_wrapper">',
      '#suffix' => '</div>',
    ];
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand('#ajax_custom_template', $build)
    );
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function myExampleAjaxCallback(array &$form, FormStateInterface $form_state) {
    // Prepare our textfield. check if the example select field has a selected option.
    if ($selectedValue = $form_state->getValue('example_select')) {
      // Get the text of the selected option.
      $selectedText = $form['example_select']['#options'][$selectedValue];
      // Place the text of the selected option in our textfield.
      $form['output']['#value'] = $selectedText;
      $form['output']['#prefix'] = '<div id="edit-output">';
      $form['output']['#suffix'] = '</div>';
    }
    $response = new AjaxResponse();
    $selector = '#ajax_append_command';
    $content = '<p>Ajax Append command</p>';
    $settings = ['home_page' => ['email' => 'jeet@gmail.com']];
    $response->addCommand(new AppendCommand($selector, $content, $settings));

    $settings = ['ajax_selected_country' => ['country' => $selectedValue]];
    $merge = TRUE;
    $response->addCommand(new SettingsCommand($settings, $merge));

    $selector = '.output_text ';
    $css = ['color' => 'red', 'vertical-align' => 'text-top'];
    $response->addCommand(new CssCommand($selector, $css));

    $selector = '#edit-ajax-append-command';
    $content = '<p>Ajax HTMLCommand content</p>';
    $settings = ['my-setting' => 'setting'];
    $response->addCommand(new HtmlCommand($selector, $content, $settings));

    $selector = '#edit-ajax-custom-template-1';
    $content = '<p>Ajax InsertCommand content</p>';
    $settings = ['my-setting' => 'setting'];
    $response->addCommand(new InsertCommand($selector, $content, $settings));

//    $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
//    $dialogText['#markup'] = "Modal Dialog Content";
//    $title = 'Modal Dialog Title';
//    $dialog_options = ['minHeight' => 200, 'resizable' => TRUE];
//    $response->addCommand(new OpenModalDialogCommand($title, $dialogText, $dialog_options));

    $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $dialogText['#markup'] = "Modal Dialog Content";
    $selector = '#drupal-dialog';
    $title = 'Dialog Title';
    $dialog_options = ['minHeight' => 200, 'resizable' => TRUE];
    $response->addCommand(new OpenDialogCommand($selector, $title, $dialogText, $dialog_options));

    $selector = '.view-id-recipe .view-content';
    $content = '<p>Replace Command content</p>';
    $settings = ['my-setting' => 'setting'];
    $response->addCommand(new HtmlCommand($selector, $content, $settings));

    //$text = 'My Text';
    //$response->addCommand(new AlertCommand($text));
    return $response;
    return $form['output'];
  }

  /**
   * Callback for link example.
   *
   * Takes different logic paths based on whether Javascript was enabled.
   * If $type == 'ajax', it tells this function that ajax.js has rewritten
   * the URL and thus we are doing an AJAX and can return an array of commands.
   *
   * @param string $nojs
   *   Either 'ajax' or 'nojs. Type is simply the normal URL argument to this
   *   URL.
   *
   * @return string|array
   *   If $type == 'ajax', returns an array of AJAX Commands.
   *   Otherwise, just returns the content, which will end up being a page.
   */
  public function ajaxLinkCallback($nojs = 'nojs') {
    // Determine whether the request is coming from AJAX or not.
    if ($nojs == 'ajax') {
      $output = $this->t("This is some content delivered via AJAX");
      $response = new AjaxResponse();
      $response->addCommand(new AppendCommand('#ajax-example-destination-div', $output));

      // See ajax_example_advanced.inc for more details on the available
      // commands and how to use them.
      // $page = array('#type' => 'ajax', '#commands' => $commands);
      // ajax_deliver($response);
      return $response;
    }
    $response = new Response($this->t("This is some content delivered via a page load."));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format' ? $value['value'] : $value));
    }
  }

}
