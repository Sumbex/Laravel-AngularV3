import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import {NgxDocViewerModule} from 'ngx-doc-viewer';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './public-master/login/login.component';
import { UsuarioService } from './servicios/usuarios.service';
import { PublicMasterComponent } from './public-master/public-master.component';
import { AboutComponent } from './public-master/about/about.component';
import { AuthMasterComponent } from './auth-master/auth-master.component';
import { CuentaSindicalComponent } from './auth-master/cuenta-sindical/cuenta-sindical.component';
import { FormularioSindicalComponent } from './auth-master/cuenta-sindical/formulario-sindical/formulario-sindical.component';
import { TablaSindicalComponent } from './auth-master/cuenta-sindical/tabla-sindical/tabla-sindical.component';
import { NavbarComponent } from './navbar/navbar.component';
import { CuentaBienestarComponent } from './auth-master/cuenta-bienestar/cuenta-bienestar.component';
import { FormularioBienestarComponent } from './auth-master/cuenta-bienestar/formulario-bienestar/formulario-bienestar.component';
import { TablaBienestarComponent } from './auth-master/cuenta-bienestar/tabla-bienestar/tabla-bienestar.component';
import { CuentaFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/cuenta-fondo-mutuo.component';
import { FormularioFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/formulario-fondo-mutuo/formulario-fondo-mutuo.component';
import { TablaFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/tabla-fondo-mutuo/tabla-fondo-mutuo.component';
import { NoEncontradoComponent } from './no-encontrado/no-encontrado.component';
import { AuthGuardService } from './guardianes/auth.guard.service';
import { JwtHelperService, JWT_OPTIONS  } from '@auth0/angular-jwt';
import { AniosService } from './servicios/anios.service';
import { TipoCuentasService } from './servicios/tipo-cuentas.service';
import { SindicalService } from './servicios/sindical.service';
import { ModalCajaChicaComponent } from './auth-master/modal-caja-chica/modal-caja-chica.component';
import { TablaCajaChicaComponent } from './auth-master/modal-caja-chica/tabla-caja-chica/tabla-caja-chica.component';
import { TotalCajaChicaComponent } from './auth-master/modal-caja-chica/total-caja-chica/total-caja-chica.component';
import { ModalInicioMesComponent } from './auth-master/modal-inicio-mes/modal-inicio-mes.component';
import { TablaInicioMesComponent } from './auth-master/modal-inicio-mes/tabla-inicio-mes/tabla-inicio-mes.component';
import { ConfiguracionesComponent } from './auth-master/configuraciones/configuraciones.component';

import { CambioContraseniaComponent } from './auth-master/configuraciones/cambio-contrasenia/cambio-contrasenia.component';

import { CajaChicaService } from './servicios/caja-chica.service';

import { TotalCuentaSindicalComponent } from './auth-master/cuenta-sindical/total-cuenta-sindical/total-cuenta-sindical.component';
import { CuentaSociosComponent } from './auth-master/cuenta-socios/cuenta-socios.component';
import { FormularioSociosComponent } from './auth-master/cuenta-socios/formulario-socios/formulario-socios.component';
import { ListarSociosComponent } from './auth-master/cuenta-socios/listar-socios/listar-socios.component';
import { ModalPrestamosSociosComponent } from './auth-master/modal-prestamos-socios/modal-prestamos-socios.component';
import { ModalCampingComponent } from './auth-master/modal-camping/modal-camping.component';
import { TablaPrestamosSociosComponent } from './auth-master/modal-prestamos-socios/tabla-prestamos-socios/tabla-prestamos-socios.component';
import { InteresPrestamoApuroEconomicoComponent } from './auth-master/modal-prestamos-socios/interes-prestamo-apuro-economico/interes-prestamo-apuro-economico.component';
import { BeneficiosSociosComponent } from './auth-master/cuenta-socios/beneficios-socios/beneficios-socios.component';
import { TablaPrestamosHistoricosSociosComponent } from './auth-master/modal-prestamos-socios/tabla-prestamos-historicos-socios/tabla-prestamos-historicos-socios.component';
import { TablasBeneficiosComponent } from './auth-master/cuenta-socios/tablas-beneficios/tablas-beneficios.component';
import { FormularioBeneficiosSocioComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-socio/formulario-beneficios-socio.component';
import { FormularioBeneficiosBeneficiarioComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-beneficiario/formulario-beneficios-beneficiario.component';
import { FormularioBeneficiosCargasComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-cargas/formulario-beneficios-cargas.component';
import { FormularioBeneficiosConyugeComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-conyuge/formulario-beneficios-conyuge.component';
import { FormularioBeneficiosPadresSuegrosComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-padres-suegros/formulario-beneficios-padres-suegros.component';
import { TablaBeneficiosCargasComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-cargas/tabla-beneficios-cargas.component';
import { TablaBeneficiosConyugeComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-conyuge/tabla-beneficios-conyuge.component';
import { TablaBeneficiosPadresSuegrosComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-padres-suegros/tabla-beneficios-padres-suegros.component';
import { TablaBeneficiosSocioComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-socio/tabla-beneficios-socio.component';
import { SociosMasterComponent } from './socios-master/socios-master.component';
import { PerfilSocioComponent } from './socios-master/perfil-socio/perfil-socio.component';
import { NavbarSocioComponent } from './socios-master/navbar-socio/navbar-socio.component';
import { FormularioBeneficiosAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-auth-socio/formulario-beneficios-auth-socio.component';
import { TablaBeneficiosAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-auth-socio/tabla-beneficios-auth-socio.component';
import { FormularioBeneficiosConyugeAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-conyuge-auth-socio/formulario-beneficios-conyuge-auth-socio.component';
import { FormularioBeneficiosBeneficiarioAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-beneficiario-auth-socio/formulario-beneficios-beneficiario-auth-socio.component';
import { FormularioBeneficiosCargasAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-cargas-auth-socio/formulario-beneficios-cargas-auth-socio.component';
import { FormularioBeneficiosPadresSuegrosAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-padres-suegros-auth-socio/formulario-beneficios-padres-suegros-auth-socio.component';
import { PortalSociosService } from './servicios/portal-socios.service';
import { AuthGuardSocioService } from './guardianes/authSocio.guard.service';
import { TablaBeneficiosBeneficiarioComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-beneficiario/tabla-beneficios-beneficiario.component';
import { SafePipe } from './safe.pipe';
import { TablaResumenSocioComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-resumen-socio/tabla-resumen-socio.component';
import { TablaBeneficiosAuthConyugeComponent } from './socios-master/perfil-socio/tabla-beneficios-auth-conyuge/tabla-beneficios-auth-conyuge.component';
import { TablaBeneficiosBeneficiarioAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-beneficiario-auth-socio/tabla-beneficios-beneficiario-auth-socio.component';
import { TablaBeneficiosCargasAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-cargas-auth-socio/tabla-beneficios-cargas-auth-socio.component';
import { TablaBeneficiosPadresSuegrosAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-padres-suegros-auth-socio/tabla-beneficios-padres-suegros-auth-socio.component';
import { BeneficiosSocioComponent } from './socios-master/beneficios-socio/beneficios-socio.component';
import { PrestamosSociosComponent } from './socios-master/beneficios-socio/prestamos-socios/prestamos-socios.component';
import { ConfiguracionSocioComponent } from './socios-master/configuracion-socio/configuracion-socio.component';
import { CambioContraseniaSocioComponent } from './socios-master/configuracion-socio/cambio-contrasenia-socio/cambio-contrasenia-socio.component';
import { CambioInformacionPersonalSocioComponent } from './socios-master/configuracion-socio/cambio-informacion-personal-socio/cambio-informacion-personal-socio.component';
import { CuentasComponent } from './socios-master/cuentas/cuentas.component';
import { SindicalComponent } from './socios-master/cuentas/sindical/sindical.component';
import { CajaChicaComponent } from './socios-master/cuentas/caja-chica/caja-chica.component';
import { CampingComponent } from './socios-master/cuentas/camping/camping.component';
import { InicioCierreMensualComponent } from './auth-master/cuenta-bienestar/inicio-cierre-mensual/inicio-cierre-mensual.component';
import { CajaChicaBienestarComponent } from './auth-master/cuenta-bienestar/caja-chica-bienestar/caja-chica-bienestar.component';
import { FallecimientoBienestarComponent } from './auth-master/cuenta-bienestar/fallecimiento-bienestar/fallecimiento-bienestar.component';
import { NacimientoBienestarComponent } from './auth-master/cuenta-bienestar/nacimiento-bienestar/nacimiento-bienestar.component';
import { GastosMedicosBienestarComponent } from './auth-master/cuenta-bienestar/gastos-medicos-bienestar/gastos-medicos-bienestar.component';
import { FormularioCajaChicaBienestarComponent } from './auth-master/cuenta-bienestar/caja-chica-bienestar/formulario-caja-chica-bienestar/formulario-caja-chica-bienestar.component';
import { TablaCajaChicaBienestarComponent } from './auth-master/cuenta-bienestar/caja-chica-bienestar/tabla-caja-chica-bienestar/tabla-caja-chica-bienestar.component';
import { FormularioFallecimientoBienestarComponent } from './auth-master/cuenta-bienestar/fallecimiento-bienestar/formulario-fallecimiento-bienestar/formulario-fallecimiento-bienestar.component';
import { TablaFallecimientoBienestarComponent } from './auth-master/cuenta-bienestar/fallecimiento-bienestar/tabla-fallecimiento-bienestar/tabla-fallecimiento-bienestar.component';
import { FormularioNacimientoBienestarComponent } from './auth-master/cuenta-bienestar/nacimiento-bienestar/formulario-nacimiento-bienestar/formulario-nacimiento-bienestar.component';
import { TablaNacimientoBienestarComponent } from './auth-master/cuenta-bienestar/nacimiento-bienestar/tabla-nacimiento-bienestar/tabla-nacimiento-bienestar.component';
import { FormularioGastosMedicosBienestarComponent } from './auth-master/cuenta-bienestar/gastos-medicos-bienestar/formulario-gastos-medicos-bienestar/formulario-gastos-medicos-bienestar.component';
import { TablaGastosMedicosBienestarComponent } from './auth-master/cuenta-bienestar/gastos-medicos-bienestar/tabla-gastos-medicos-bienestar/tabla-gastos-medicos-bienestar.component';
import { BeneficiosCobradosComponent } from './socios-master/beneficios-socio/beneficios-cobrados/beneficios-cobrados.component';
import { FondosMutuosSocioComponent } from './socios-master/beneficios-socio/fondos-mutuos-socio/fondos-mutuos-socio.component';
import { BryanBienestarService } from './servicios/bryans-bienestar.service';
import { CuentaBienestarSociosComponent } from './socios-master/cuentas/bienestar/cuenta-bienestar-socios/cuenta-bienestar-socios.component';
import { CajaBienestarSociosComponent } from './socios-master/cuentas/bienestar/caja-bienestar-socios/caja-bienestar-socios.component';
import { TablaDesvinculadosConsorcioComponent } from './auth-master/cuenta-fondo-mutuo/tabla-desvinculados-consorcio/tabla-desvinculados-consorcio.component';
import { ConsorcioComponent } from './socios-master/cuentas/consorcio/consorcio.component';
import { BryanConsorcioService } from './servicios/bryan-consorcio.service';
import { GraficoComponenteComponent } from './socios-master/grafico/grafico-componente/grafico-componente.component';
import { GeneralReunionComponent } from './socios-master/reuniones/general-reunion/general-reunion.component';
import { DetalleReunionComponent } from './socios-master/reuniones/detalle-reunion/detalle-reunion.component';
import { HistorialDetalleReunionComponent } from './socios-master/reuniones/historial-detalle-reunion/historial-detalle-reunion.component';
import { ReunionesService } from './servicios/reuniones.service';
import { AuthInterceptorService } from './servicios/auth-interceptor.service';
import { SecretariaComponent } from './auth-master/secretaria/secretaria.component';
import { NuevaReunionComponent } from './auth-master/secretaria/nueva-reunion/nueva-reunion.component';
import { EditarReunionComponent } from './auth-master/secretaria/editar-reunion/editar-reunion.component';
import { AsistenciaReunionComponent } from './auth-master/secretaria/asistencia-reunion/asistencia-reunion.component';
import { HistorialReunionComponent } from './auth-master/secretaria/historial-reunion/historial-reunion.component';
import { SecretariaService } from './servicios/secretaria.service';
import { DesvinculadosComponent } from './socios-master/cuentas/consorcio/desvinculados/desvinculados.component';
import { ModalGastosOperacionalesComponent } from './modal-gastos-operacionales/modal-gastos-operacionales.component';
import { GastosOperacionalesComponent } from './socios-master/cuentas/gastos-operacionales/gastos-operacionales.component';
import { AcuerdoAsambleaComponent } from './auth-master/secretaria/acuerdo-asamblea/acuerdo-asamblea.component';
import { ActasComponent } from './auth-master/secretaria/actas/actas.component';
import { LeyesLaboralesComponent } from './auth-master/secretaria/leyes-laborales/leyes-laborales.component';
import { DirectorioComponent } from './auth-master/secretaria/directorio/directorio.component';
import { AcuerdosSocioComponent } from './socios-master/secretaria/acuerdos-socio/acuerdos-socio.component';
import { ActasSocioComponent } from './socios-master/secretaria/actas-socio/actas-socio.component';
import { DirectorioSocioComponent } from './socios-master/secretaria/directorio-socio/directorio-socio.component';
import { LeyesSocioComponent } from './socios-master/secretaria/leyes-socio/leyes-socio.component';
import { ModalDetallePagoConsorcioComponent } from './auth-master/modal-detalle-pago-consorcio/modal-detalle-pago-consorcio.component';
import { ArchivadorComponent } from './auth-master/cuenta-sindical/archivador/archivador.component';
import { CrearEmpleadoComponent } from './auth-master/liquidaciones/crear-empleado/crear-empleado.component';
import { MenuComponent } from './auth-master/liquidaciones/menu/menu.component';
import { HaberesComponent } from './auth-master/liquidaciones/haberes/haberes.component';
import { DescuentosComponent } from './auth-master/liquidaciones/descuentos/descuentos.component';
import { LiquidacionComponent } from './auth-master/liquidaciones/liquidacion/liquidacion.component';
import { from } from 'rxjs';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    PublicMasterComponent,
    AboutComponent,
    AuthMasterComponent,
    CuentaSindicalComponent,
    FormularioSindicalComponent,
    TablaSindicalComponent,
    NavbarComponent,
    CuentaBienestarComponent,
    FormularioBienestarComponent,
    TablaBienestarComponent,
    CuentaFondoMutuoComponent,
    FormularioFondoMutuoComponent,
    TablaFondoMutuoComponent,
    NoEncontradoComponent,
    ModalCajaChicaComponent,
    TablaCajaChicaComponent,
    TotalCajaChicaComponent,
    ModalInicioMesComponent,
    TablaInicioMesComponent,
    ConfiguracionesComponent,
    CambioContraseniaComponent,
    TotalCuentaSindicalComponent,
    CuentaSociosComponent,
    FormularioSociosComponent,
    ListarSociosComponent,
    ModalPrestamosSociosComponent,
    ModalCampingComponent,
    TablaPrestamosSociosComponent,
    InteresPrestamoApuroEconomicoComponent,
    BeneficiosSociosComponent,
    TablaPrestamosHistoricosSociosComponent,
    TablasBeneficiosComponent,
    FormularioBeneficiosSocioComponent,
    FormularioBeneficiosBeneficiarioComponent,
    FormularioBeneficiosCargasComponent,
    FormularioBeneficiosConyugeComponent,
    FormularioBeneficiosPadresSuegrosComponent,
    TablaBeneficiosCargasComponent,
    TablaBeneficiosConyugeComponent,
    TablaBeneficiosPadresSuegrosComponent,
    TablaBeneficiosSocioComponent,
    TablaBeneficiosBeneficiarioComponent,
    SociosMasterComponent,
    PerfilSocioComponent,
    NavbarSocioComponent,
    FormularioBeneficiosAuthSocioComponent,
    TablaBeneficiosAuthSocioComponent,
    FormularioBeneficiosConyugeAuthSocioComponent,
    FormularioBeneficiosBeneficiarioAuthSocioComponent,
    FormularioBeneficiosCargasAuthSocioComponent,
    FormularioBeneficiosPadresSuegrosAuthSocioComponent,
    SafePipe,
    TablaResumenSocioComponent,
    TablaBeneficiosAuthConyugeComponent,
    TablaBeneficiosBeneficiarioAuthSocioComponent,
    TablaBeneficiosCargasAuthSocioComponent,
    TablaBeneficiosPadresSuegrosAuthSocioComponent,
    BeneficiosSocioComponent,
    PrestamosSociosComponent,
    ConfiguracionSocioComponent,
    CambioContraseniaSocioComponent,
    CambioInformacionPersonalSocioComponent,
    CuentasComponent,
    SindicalComponent,
    CajaChicaComponent,
    CampingComponent,
    InicioCierreMensualComponent,
    CajaChicaBienestarComponent,
    FallecimientoBienestarComponent,
    NacimientoBienestarComponent,
    GastosMedicosBienestarComponent,
    FormularioCajaChicaBienestarComponent,
    TablaCajaChicaBienestarComponent,
    FormularioFallecimientoBienestarComponent,
    TablaFallecimientoBienestarComponent,
    FormularioNacimientoBienestarComponent,
    TablaNacimientoBienestarComponent,
    FormularioGastosMedicosBienestarComponent,
    TablaGastosMedicosBienestarComponent,
    BeneficiosCobradosComponent,
    FondosMutuosSocioComponent,
    CuentaBienestarSociosComponent,
    CajaBienestarSociosComponent,
    TablaDesvinculadosConsorcioComponent,
    ConsorcioComponent,
    GraficoComponenteComponent,
    GeneralReunionComponent,
    DetalleReunionComponent,
    HistorialDetalleReunionComponent,
    SecretariaComponent,
    NuevaReunionComponent,
    EditarReunionComponent,
    AsistenciaReunionComponent,
    HistorialReunionComponent,
    DesvinculadosComponent,
    ModalGastosOperacionalesComponent,
    GastosOperacionalesComponent,
    AcuerdoAsambleaComponent,
    ActasComponent,
    LeyesLaboralesComponent,
    DirectorioComponent,
    AcuerdosSocioComponent,
    ActasSocioComponent,
    DirectorioSocioComponent,
    LeyesSocioComponent,
    ModalDetallePagoConsorcioComponent,
    ArchivadorComponent,
    CrearEmpleadoComponent,
    MenuComponent,
    HaberesComponent,
    DescuentosComponent,
    LiquidacionComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    NgbModule,
    NgxDocViewerModule
  ],
  providers: [
    UsuarioService,
    AuthGuardService,
    AuthGuardSocioService,
    { provide: JWT_OPTIONS, useValue: JWT_OPTIONS },
    JwtHelperService,
    AniosService,
    TipoCuentasService,
    SindicalService,
    CajaChicaService,
    PortalSociosService,
    BryanBienestarService,
    BryanConsorcioService,
    ReunionesService,
    SecretariaService,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptorService,
      multi: true
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
