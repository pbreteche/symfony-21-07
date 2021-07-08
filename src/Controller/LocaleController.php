<?php

namespace App\Controller;

use App\Service\AvailableLocales;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocaleController extends AbstractController
{
    /**
     * @var \App\Service\AvailableLocales
     */
    private $locales;

    public function __construct(AvailableLocales $locales)
    {
        $this->locales = $locales;
    }

    /**
     * @Route("/locale/select")
     */
    public function validate(
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $locale = $request->request->get('locale', $this->locales->getDefault());
        try {
            $language = Languages::getName($locale, $locale);
        } catch (MissingResourceException $exception) {
            $language = $locale;
        }

        if (!in_array($locale, $this->locales->getAll())) {
            $this->addFlash('error', $translator->trans('locale.choice_error', [
                'language' => $language,
            ]));
        } else {
            $request->getSession()->set('locale', $locale);
            $this->addFlash('success', $translator->trans('locale.choice_success', [
                'language' => $language,
            ], null, $locale));
        }

        return $this->redirectToRoute('app_default_index');
    }

    public function select()
    {
        return $this->render('locale/select.html.twig', [
            'locales' => $this->locales->getChoices(),
        ]);
    }
}