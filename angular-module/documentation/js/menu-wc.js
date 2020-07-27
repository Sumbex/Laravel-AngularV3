'use strict';


customElements.define('compodoc-menu', class extends HTMLElement {
    constructor() {
        super();
        this.isNormalMode = this.getAttribute('mode') === 'normal';
    }

    connectedCallback() {
        this.render(this.isNormalMode);
    }

    render(isNormalMode) {
        let tp = lithtml.html(`
        <nav>
            <ul class="list">
                <li class="title">
                    <a href="index.html" data-type="index-link">angular-module documentation</a>
                </li>

                <li class="divider"></li>
                ${ isNormalMode ? `<div id="book-search-input" role="search"><input type="text" placeholder="Type to search"></div>` : '' }
                <li class="chapter">
                    <a data-type="chapter-link" href="index.html"><span class="icon ion-ios-home"></span>Getting started</a>
                    <ul class="links">
                        <li class="link">
                            <a href="overview.html" data-type="chapter-link">
                                <span class="icon ion-ios-keypad"></span>Overview
                            </a>
                        </li>
                        <li class="link">
                            <a href="index.html" data-type="chapter-link">
                                <span class="icon ion-ios-paper"></span>README
                            </a>
                        </li>
                                <li class="link">
                                    <a href="dependencies.html" data-type="chapter-link">
                                        <span class="icon ion-ios-list"></span>Dependencies
                                    </a>
                                </li>
                    </ul>
                </li>
                    <li class="chapter modules">
                        <a data-type="chapter-link" href="modules.html">
                            <div class="menu-toggler linked" data-toggle="collapse" ${ isNormalMode ?
                                'data-target="#modules-links"' : 'data-target="#xs-modules-links"' }>
                                <span class="icon ion-ios-archive"></span>
                                <span class="link-name">Modules</span>
                                <span class="icon ion-ios-arrow-down"></span>
                            </div>
                        </a>
                        <ul class="links collapse " ${ isNormalMode ? 'id="modules-links"' : 'id="xs-modules-links"' }>
                            <li class="link">
                                <a href="modules/AppModule.html" data-type="entity-link">AppModule</a>
                                    <li class="chapter inner">
                                        <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ?
                                            'data-target="#components-links-module-AppModule-f785d02251f483f39988e231c24388a9"' : 'data-target="#xs-components-links-module-AppModule-f785d02251f483f39988e231c24388a9"' }>
                                            <span class="icon ion-md-cog"></span>
                                            <span>Components</span>
                                            <span class="icon ion-ios-arrow-down"></span>
                                        </div>
                                        <ul class="links collapse" ${ isNormalMode ? 'id="components-links-module-AppModule-f785d02251f483f39988e231c24388a9"' :
                                            'id="xs-components-links-module-AppModule-f785d02251f483f39988e231c24388a9"' }>
                                            <li class="link">
                                                <a href="components/AboutComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">AboutComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ActasComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ActasComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ActasSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ActasSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/AcuerdoAsambleaComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">AcuerdoAsambleaComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/AcuerdosSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">AcuerdosSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/AppComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">AppComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ArchivadorComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ArchivadorComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/AsistenciaReunionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">AsistenciaReunionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/AuthMasterComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">AuthMasterComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/BeneficiosCobradosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">BeneficiosCobradosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/BeneficiosSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">BeneficiosSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/BeneficiosSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">BeneficiosSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CajaBienestarSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CajaBienestarSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CajaChicaBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CajaChicaBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CajaChicaComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CajaChicaComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CambioContraseniaComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CambioContraseniaComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CambioContraseniaSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CambioContraseniaSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CambioInformacionPersonalSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CambioInformacionPersonalSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CampingComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CampingComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ConfiguracionSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ConfiguracionSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ConfiguracionesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ConfiguracionesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ConsorcioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ConsorcioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CrearEmpleadoComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CrearEmpleadoComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CuentaBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CuentaBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CuentaBienestarSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CuentaBienestarSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CuentaFondoMutuoComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CuentaFondoMutuoComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CuentaSindicalComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CuentaSindicalComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CuentaSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CuentaSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/CuentasComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">CuentasComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/DescuentosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">DescuentosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/DesvinculadosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">DesvinculadosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/DetalleReunionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">DetalleReunionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/DirectorioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">DirectorioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/DirectorioSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">DirectorioSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/EditarReunionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">EditarReunionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FallecimientoBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FallecimientoBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FondosMutuosSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FondosMutuosSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosBeneficiarioAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosBeneficiarioAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosBeneficiarioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosBeneficiarioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosCargasAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosCargasAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosCargasComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosCargasComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosConyugeAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosConyugeAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosConyugeComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosConyugeComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosPadresSuegrosAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosPadresSuegrosAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosPadresSuegrosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosPadresSuegrosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBeneficiosSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBeneficiosSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioCajaChicaBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioCajaChicaBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioFallecimientoBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioFallecimientoBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioFondoMutuoComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioFondoMutuoComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioGastosMedicosBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioGastosMedicosBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioNacimientoBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioNacimientoBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioSindicalComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioSindicalComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/FormularioSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">FormularioSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/GastosMedicosBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">GastosMedicosBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/GastosOperacionalesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">GastosOperacionalesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/GeneralReunionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">GeneralReunionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/GraficoComponenteComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">GraficoComponenteComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/HaberesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">HaberesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/HistorialDetalleReunionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">HistorialDetalleReunionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/HistorialLiquidacionesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">HistorialLiquidacionesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/HistorialReunionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">HistorialReunionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/InicioCierreMensualComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">InicioCierreMensualComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/InteresPrestamoApuroEconomicoComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">InteresPrestamoApuroEconomicoComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/LeyesLaboralesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">LeyesLaboralesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/LeyesSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">LeyesSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/LiquidacionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">LiquidacionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ListarSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ListarSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/LoginComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">LoginComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/MenuComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">MenuComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ModalCajaChicaComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ModalCajaChicaComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ModalCampingComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ModalCampingComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ModalDetallePagoConsorcioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ModalDetallePagoConsorcioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ModalGastosOperacionalesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ModalGastosOperacionalesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ModalInicioMesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ModalInicioMesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/ModalPrestamosSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">ModalPrestamosSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/NacimientoBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">NacimientoBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/NavbarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">NavbarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/NavbarSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">NavbarSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/NoEncontradoComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">NoEncontradoComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/NuevaReunionComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">NuevaReunionComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/PerfilSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">PerfilSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/PrestamosSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">PrestamosSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/PublicMasterComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">PublicMasterComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/SecretariaComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">SecretariaComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/SindicalComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">SindicalComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/SociosMasterComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">SociosMasterComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosAuthConyugeComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosAuthConyugeComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosBeneficiarioAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosBeneficiarioAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosBeneficiarioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosBeneficiarioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosCargasAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosCargasAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosCargasComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosCargasComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosConyugeComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosConyugeComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosPadresSuegrosAuthSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosPadresSuegrosAuthSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosPadresSuegrosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosPadresSuegrosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBeneficiosSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBeneficiosSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaCajaChicaBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaCajaChicaBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaCajaChicaComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaCajaChicaComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaDesvinculadosConsorcioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaDesvinculadosConsorcioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaFallecimientoBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaFallecimientoBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaFondoMutuoComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaFondoMutuoComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaGastosMedicosBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaGastosMedicosBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaInicioMesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaInicioMesComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaNacimientoBienestarComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaNacimientoBienestarComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaPrestamosHistoricosSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaPrestamosHistoricosSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaPrestamosSociosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaPrestamosSociosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaResumenSocioComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaResumenSocioComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablaSindicalComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablaSindicalComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TablasBeneficiosComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TablasBeneficiosComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TemasComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TemasComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TotalCajaChicaComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TotalCajaChicaComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/TotalCuentaSindicalComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">TotalCuentaSindicalComponent</a>
                                            </li>
                                            <li class="link">
                                                <a href="components/VotacionesComponent.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">VotacionesComponent</a>
                                            </li>
                                        </ul>
                                    </li>
                                <li class="chapter inner">
                                    <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ?
                                        'data-target="#injectables-links-module-AppModule-f785d02251f483f39988e231c24388a9"' : 'data-target="#xs-injectables-links-module-AppModule-f785d02251f483f39988e231c24388a9"' }>
                                        <span class="icon ion-md-arrow-round-down"></span>
                                        <span>Injectables</span>
                                        <span class="icon ion-ios-arrow-down"></span>
                                    </div>
                                    <ul class="links collapse" ${ isNormalMode ? 'id="injectables-links-module-AppModule-f785d02251f483f39988e231c24388a9"' :
                                        'id="xs-injectables-links-module-AppModule-f785d02251f483f39988e231c24388a9"' }>
                                        <li class="link">
                                            <a href="injectables/AniosService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>AniosService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/BryanBienestarService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>BryanBienestarService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/BryanConsorcioService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>BryanConsorcioService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/CajaChicaService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>CajaChicaService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/PortalSociosService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>PortalSociosService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/ReunionesService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>ReunionesService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/SecretariaService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>SecretariaService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/SindicalService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>SindicalService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/TipoCuentasService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>TipoCuentasService</a>
                                        </li>
                                        <li class="link">
                                            <a href="injectables/UsuarioService.html"
                                                data-type="entity-link" data-context="sub-entity" data-context-id="modules" }>UsuarioService</a>
                                        </li>
                                    </ul>
                                </li>
                                    <li class="chapter inner">
                                        <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ?
                                            'data-target="#pipes-links-module-AppModule-f785d02251f483f39988e231c24388a9"' : 'data-target="#xs-pipes-links-module-AppModule-f785d02251f483f39988e231c24388a9"' }>
                                            <span class="icon ion-md-add"></span>
                                            <span>Pipes</span>
                                            <span class="icon ion-ios-arrow-down"></span>
                                        </div>
                                        <ul class="links collapse" ${ isNormalMode ? 'id="pipes-links-module-AppModule-f785d02251f483f39988e231c24388a9"' :
                                            'id="xs-pipes-links-module-AppModule-f785d02251f483f39988e231c24388a9"' }>
                                            <li class="link">
                                                <a href="pipes/SafePipe.html"
                                                    data-type="entity-link" data-context="sub-entity" data-context-id="modules">SafePipe</a>
                                            </li>
                                        </ul>
                                    </li>
                            </li>
                            <li class="link">
                                <a href="modules/AppRoutingModule.html" data-type="entity-link">AppRoutingModule</a>
                            </li>
                </ul>
                </li>
                    <li class="chapter">
                        <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ? 'data-target="#classes-links"' :
                            'data-target="#xs-classes-links"' }>
                            <span class="icon ion-ios-paper"></span>
                            <span>Classes</span>
                            <span class="icon ion-ios-arrow-down"></span>
                        </div>
                        <ul class="links collapse " ${ isNormalMode ? 'id="classes-links"' : 'id="xs-classes-links"' }>
                            <li class="link">
                                <a href="classes/Anios.html" data-type="entity-link">Anios</a>
                            </li>
                            <li class="link">
                                <a href="classes/cajaChicaSindical.html" data-type="entity-link">cajaChicaSindical</a>
                            </li>
                            <li class="link">
                                <a href="classes/cajaChicaSindicalTotales.html" data-type="entity-link">cajaChicaSindicalTotales</a>
                            </li>
                            <li class="link">
                                <a href="classes/Definicion.html" data-type="entity-link">Definicion</a>
                            </li>
                            <li class="link">
                                <a href="classes/Detalle.html" data-type="entity-link">Detalle</a>
                            </li>
                            <li class="link">
                                <a href="classes/InicioCierre.html" data-type="entity-link">InicioCierre</a>
                            </li>
                            <li class="link">
                                <a href="classes/Meses.html" data-type="entity-link">Meses</a>
                            </li>
                            <li class="link">
                                <a href="classes/Prestamos.html" data-type="entity-link">Prestamos</a>
                            </li>
                            <li class="link">
                                <a href="classes/Sindical.html" data-type="entity-link">Sindical</a>
                            </li>
                            <li class="link">
                                <a href="classes/Sindical-1.html" data-type="entity-link">Sindical</a>
                            </li>
                            <li class="link">
                                <a href="classes/Usuario.html" data-type="entity-link">Usuario</a>
                            </li>
                        </ul>
                    </li>
                        <li class="chapter">
                            <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ? 'data-target="#injectables-links"' :
                                'data-target="#xs-injectables-links"' }>
                                <span class="icon ion-md-arrow-round-down"></span>
                                <span>Injectables</span>
                                <span class="icon ion-ios-arrow-down"></span>
                            </div>
                            <ul class="links collapse " ${ isNormalMode ? 'id="injectables-links"' : 'id="xs-injectables-links"' }>
                                <li class="link">
                                    <a href="injectables/AcuerdoAsambleaService.html" data-type="entity-link">AcuerdoAsambleaService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/AniosService.html" data-type="entity-link">AniosService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/ArchivadorService.html" data-type="entity-link">ArchivadorService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/BienestarService.html" data-type="entity-link">BienestarService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/BryanBienestarService.html" data-type="entity-link">BryanBienestarService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/BryanConsorcioService.html" data-type="entity-link">BryanConsorcioService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/CajaChicaService.html" data-type="entity-link">CajaChicaService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/ConsorcioService.html" data-type="entity-link">ConsorcioService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/CsDetalleCampingService.html" data-type="entity-link">CsDetalleCampingService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/DirectorioService.html" data-type="entity-link">DirectorioService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/LeyesService.html" data-type="entity-link">LeyesService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/LiquidacionEmpanadaService.html" data-type="entity-link">LiquidacionEmpanadaService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/LiquidacionJuanitoService.html" data-type="entity-link">LiquidacionJuanitoService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/LiquidacionMarroService.html" data-type="entity-link">LiquidacionMarroService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/PortalSociosService.html" data-type="entity-link">PortalSociosService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/ReunionesService.html" data-type="entity-link">ReunionesService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/SecretariaService.html" data-type="entity-link">SecretariaService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/SindicalService.html" data-type="entity-link">SindicalService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/SociosService.html" data-type="entity-link">SociosService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/TestService.html" data-type="entity-link">TestService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/TipoCuentasService.html" data-type="entity-link">TipoCuentasService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/UsuarioService.html" data-type="entity-link">UsuarioService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/ValidarUsuarioService.html" data-type="entity-link">ValidarUsuarioService</a>
                                </li>
                                <li class="link">
                                    <a href="injectables/VotacionesBryanmService.html" data-type="entity-link">VotacionesBryanmService</a>
                                </li>
                            </ul>
                        </li>
                    <li class="chapter">
                        <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ? 'data-target="#interceptors-links"' :
                            'data-target="#xs-interceptors-links"' }>
                            <span class="icon ion-ios-swap"></span>
                            <span>Interceptors</span>
                            <span class="icon ion-ios-arrow-down"></span>
                        </div>
                        <ul class="links collapse " ${ isNormalMode ? 'id="interceptors-links"' : 'id="xs-interceptors-links"' }>
                            <li class="link">
                                <a href="interceptors/AuthInterceptorService.html" data-type="entity-link">AuthInterceptorService</a>
                            </li>
                        </ul>
                    </li>
                    <li class="chapter">
                        <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ? 'data-target="#guards-links"' :
                            'data-target="#xs-guards-links"' }>
                            <span class="icon ion-ios-lock"></span>
                            <span>Guards</span>
                            <span class="icon ion-ios-arrow-down"></span>
                        </div>
                        <ul class="links collapse " ${ isNormalMode ? 'id="guards-links"' : 'id="xs-guards-links"' }>
                            <li class="link">
                                <a href="guards/AuthGuardService.html" data-type="entity-link">AuthGuardService</a>
                            </li>
                            <li class="link">
                                <a href="guards/AuthGuardSocioService.html" data-type="entity-link">AuthGuardSocioService</a>
                            </li>
                        </ul>
                    </li>
                    <li class="chapter">
                        <div class="simple menu-toggler" data-toggle="collapse" ${ isNormalMode ? 'data-target="#miscellaneous-links"'
                            : 'data-target="#xs-miscellaneous-links"' }>
                            <span class="icon ion-ios-cube"></span>
                            <span>Miscellaneous</span>
                            <span class="icon ion-ios-arrow-down"></span>
                        </div>
                        <ul class="links collapse " ${ isNormalMode ? 'id="miscellaneous-links"' : 'id="xs-miscellaneous-links"' }>
                            <li class="link">
                                <a href="miscellaneous/variables.html" data-type="entity-link">Variables</a>
                            </li>
                        </ul>
                    </li>
                        <li class="chapter">
                            <a data-type="chapter-link" href="routes.html"><span class="icon ion-ios-git-branch"></span>Routes</a>
                        </li>
                    <li class="chapter">
                        <a data-type="chapter-link" href="coverage.html"><span class="icon ion-ios-stats"></span>Documentation coverage</a>
                    </li>
                    <li class="divider"></li>
                    <li class="copyright">
                        Documentation generated using <a href="https://compodoc.app/" target="_blank">
                            <img data-src="images/compodoc-vectorise.png" class="img-responsive" data-type="compodoc-logo">
                        </a>
                    </li>
            </ul>
        </nav>
        `);
        this.innerHTML = tp.strings;
    }
});