# TO CREATE A PAGE WITH A ROUTE NAME

## IN THE CONTROLLER

```php
#[Route('/my/route', name: 'my_route_name')]
    public function test(RequestStack $requestStack, PageRepository $pageRepository, PageService $pageService): Response
    {

        $currentRequest = $requestStack->getCurrentRequest();
        $routeName = $currentRequest->get('_route');

        $page = $pageRepository->findOneBy(['devCodeRouteName' => $routeName]);

        $elements = $pageService->getPageElements($page);

        $elements['template'] = $page->getTemplate();

        if (null === $elements['template']) {
            $elements['template'] = UtilsEnum::PAGE_DEFAULT_TEMPLATE;
        }

        return $this->render($elements['template'], $elements);
    }
```

## FOR THE PAGE

Make sure the page has a the same route name for the devCodeRouteName property and the same slug in slug property.
For exemple according the example above, the page should have the following properties:

- devCodeRouteName: my_route_name
- slug: my/route (do not put a slash at the beginning of the slug in the database for the property slug)