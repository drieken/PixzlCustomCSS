<?php
declare(strict_types=1);

namespace PixzlCustomCSS\Controller\Admin;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\System\SystemConfig\SystemConfigServiceInterface;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"admin"})
 */
class CustomCssController extends AbstractController
{
	/**
	 * @var SystemConfigServiceInterface
	 */
	private $systemConfigRepository;

	public function __construct(SystemConfigService $systemConfigService)
	{
		$this->systemConfigRepository = $systemConfigService;
	}

	/**
	 * @Route("/admin/pixzl-custom-css", name="admin.pixzl-custom-css.index", methods={"GET", "POST"})
	 */
	public function index(Request $request): Response
	{
		if ($request->getMethod() === Request::METHOD_POST) {
			$fileName = $request->request->get('fileName');
			$customCss = $request->request->get('customCss');

			$this->systemConfigRepository->create([
				'configurationKey' => 'pixzl_custom_css.' . $fileName,
				'configurationValue' => $customCss,
			], $request->getContext());

			return new Response('CSS saved successfully.');
		}

		return $this->render('@PixzlCustomCSS/admin/pixzl_custom_css/index.html.twig');
	}

	/**
	 * @Route("/admin/pixzl-custom-css/{fileName}", name="admin.pixzl-custom-css.edit", methods={"GET", "POST"})
	 */
	public function edit(Request $request, string $fileName): Response
	{
		if ($request->getMethod() === Request::METHOD_POST) {
			$customCss = $request->request->get('customCss');

			$this->systemConfigRepository->create([
				'configurationKey' => 'pixzl_custom_css.' . $fileName,
				'configurationValue' => $customCss,
			], $request->getContext());

			return new Response('CSS saved successfully.');
		}

		$customCss = $this->systemConfigRepository->get('pixzl_custom_css.' . $fileName, $request->getContext());

		return $this->render('@PixzlCustomCSS/admin/pixzl_custom_css/index.html.twig', [
			'fileName' => $fileName,
			'customCss' => $customCss
		]);
	}
}
