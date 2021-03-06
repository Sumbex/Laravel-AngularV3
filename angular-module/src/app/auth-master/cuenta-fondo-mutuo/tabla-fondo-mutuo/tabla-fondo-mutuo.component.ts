import { Component, OnInit } from '@angular/core';
import { AniosService } from 'src/app/servicios/anios.service';
import { ConsorcioService } from 'src/app/servicios/consorcio.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';

@Component({
  selector: 'app-tabla-fondo-mutuo',
  templateUrl: './tabla-fondo-mutuo.component.html',
  styleUrls: ['./tabla-fondo-mutuo.component.css']
})
export class TablaFondoMutuoComponent implements OnInit {

  socios;
  sociosMensual;
  sociosAnual;
  search: string = '';
  mod_editar = null;
  mod_validar = null;
  blockLoad = false;
  blockLoad2 = false;
  contador = 3;

  //actualizar_socio
  rut;
  nombres;
  a_paterno;
  a_materno;
  fecha_nacimiento;
  fecha_ingreso;
  fecha_egreso;

  //para validar usuario
  user: object = [];
  load: boolean = false;
  modalReference = null;
  m_val = null;
  mod_opcion = null;

  closeResult: string;
  pass: string = '';
  btn_validar: boolean = false;

  currentLesson: string;

  buttonStatus = false;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  // estado_socio: object = ['estado'];
  ver_load: boolean = true;
  ver_estado_soc: boolean = false;

  //variables select año y mes
  anio; anios;
  mes; meses;
  idAnioActual;
  idMesActual;
  suc_res1 = false;
  suc_res2 = false;
  //ingresar consorcio
  blockIngreso: boolean = false;

  //pago beneficio
  modalPagoBeneficio;
  tipoPago = 2;
  tipoRetiro = '0';
  tipoPorc = '';
  mesBeneficio;
  montoBeneficio;
  montoFinal = '0';
  socioPB;
  actualizarLoad = false;
  actualizarLoad2 = false;
  socio = '0';
  descripcionDesc = '';
  ocultarDescripcion = true;
  blockLoad3 = true;

  //calcular acumulado
  modalAcumulado;
  generar;
  anioSig = '';
  generarAcumulado;
  actualizarLoad3 = false;
  totalAhorro;

  // PAGAR DIA SUELDO EN C.S.
  modalPago;
  fechaSocio;
  nDocumento;
  archivoDocumento;
  montoDiaSueldoSocio;
  montoPrestamoSocio = '0';




  constructor(
    private _time: AniosService,
    private _consorcioService: ConsorcioService,
    config: NgbModalConfig,
    private modalService: NgbModal,
    private _validarusuario: ValidarUsuarioService) {
    config.backdrop = 'static';
    config.keyboard = false;

  }

  ngOnInit() {

    if (localStorage.getItem('token') == '') {
      alert("La sesión ya expiro!");

      location.reload();
    }

    this.llenar_select();
  }

  onSelectImage(event) {
    this.archivoDocumento = event.srcElement.files[0];
  }

  openPagoBeneficio(PagoBeneficio) {
    this.modalPagoBeneficio = this.modalService.open(PagoBeneficio, { size: 'lg' });
    this.socioDesvinculado();
    this.usuario_logeado();
  }

  cerrarPagoBeneficio() {
    this.modalPagoBeneficio.close();
  }

  openAcumulado(Acumulado) {
    this.modalAcumulado = this.modalService.open(Acumulado, { size: 'sm' });
    // this.socioDesvinculado();
    this.usuario_logeado();
  }
  openPago(Pago) {
    this.modalPago = this.modalService.open(Pago, { size: 'lg' });
    // this.socioDesvinculado();
    this.usuario_logeado();
  }

  cerrarPago(){
    this.modalPago.close();
  }

  cerrarAcumulado() {
    this.modalAcumulado.close();
    this.anioSig='';
  }


  limpiarPagoBeneficio() {
    this.tipoPorc = '';
    this.tipoRetiro = '0';
    this.montoBeneficio = '';
    this.montoFinal = '0';
    this.socio = '0';
  }

  listar() {
    this.blockLoad2 = true;
    this._consorcioService.getTablaConsorcios(this.anio).subscribe(
      response => {
        this.socios = response;
        console.log(this.socios);
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }

  listarMensual() {
    // this.blockLoad2 = true;
    this._consorcioService.getTablaConsorciosTotalesMensuales(this.anio).subscribe(
      response => {
        this.sociosMensual = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }

  listarAnual() {
    // this.blockLoad2 = true;
    this._consorcioService.getTablaConsorciosTotalAnual(this.anio).subscribe(
      response => {
        this.sociosAnual = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }

  filtrar() {

    this.blockLoad = true;
    if (this.search == '') {
      alert("Ingrese un nombre para filtrar");
      this.blockLoad = false;
      return false;
    } else {
      this._consorcioService.getTablaConsorcio(this.anio, this.search).subscribe(
        response => {
          this.socios = response;
          this.blockLoad = false;

        }
      )
    }
  }

  llenar_select() {

    this._time.getAnios().subscribe(
      response => {
        this.anios = response;


        this._time.getAnioActual().subscribe(
          (response: { id }) => {
            this.anio = response.id;
            this.suc_res1 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);

          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )

    this._time.getMeses().subscribe(
      response => {
        this.meses = response;

        this._time.getMesActual().subscribe(
          (response: { id }) => {
            this.mes = response.id;
            this.suc_res2 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);
          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )
  }

  listo_para_listar(res1, res2) {
    if (res1 == true && res2 == true) {
      this.listar();
      this.listarMensual();
      this.listarAnual();
      this.ahorroDiaSueldo();
    }
  }

  btn_reload() {

    this.contador = 3;
    this.listo_para_listar(this.suc_res1, this.suc_res2);
    this.ocultarDescripcion = true;

  }

  ConvertToInt(val) {
    return parseInt(val);
  }

  usuario_logeado() {

    this._validarusuario.usuario_logeado().subscribe((val: object) => {

      this.user = val;

    }, response => { console.log("POST call in error", response); }, () => {
      console.log("The POST success.");
    });
  }

  calcularPagoBeneficio(validar) {
    this.m_val = this.modalService.open(validar, { size: 'sm', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {

      this.m_val = this.modalService.open(validar, { size: 'sm' });
      this.load = true;
      this.buttonStatus = true;

      const formData = new FormData();
      formData.append('rut', this.user['rut']);
      formData.append('password', this.pass);
      formData.append('estado', 'calcular_descuento_cc');
      var confirmar = this.confirmar();

      if (confirmar == true) {

        this._validarusuario.validar_usuario(formData).subscribe((val) => {

          //si tiene acceso
          if (val > 0) {

            this.load = false;
            this.buttonStatus = false;
            this.pass = "";
            this.m_val.close();

            this.actualizarLoad = true;
            this.blockLoad = true;
            this._consorcioService.calcularPagoBeneficio(this.tipoPorc, this.mesBeneficio, this.anio).subscribe((response) => {
              if (response.estado == 'failed') {
                alert(response.mensaje);
                this.actualizarLoad = false;
                this.blockLoad = false;
              }
              if (response.estado == 'success') {
                alert(response.mensaje);
                this.actualizarLoad = false;
                this.blockLoad = false;
                this.montoBeneficio = response.monto_beneficio;
                this.btn_reload();
              }
            },
              error => {
                console.log(error);
                alert("Supero el limite de 60 segundos al generar la insercion masiva");
                this.actualizarLoad = false;
                this.blockLoad = false;
                return false;
              }
            );

          } else {
            alert("Acceso denegado");
            this.load = false;
            this.buttonStatus = false;
            this.pass = "";
            this.m_val.close();
            return false;
          }

        },
          response => { console.log("POST call in error", response); }, () => {
            console.log("The POST success.");
          });
        return false;
      } else {
        this.load = false;
        this.buttonStatus = false;
        this.m_val.close();
      }


    }, (reason) => {
      console.log(`${reason}`);
    });

  }

  confirmar() {
    var r = confirm("¿ESTA SEGURO QUE DESEA GENERAR EL CALCULO MASIVO?");
    if (r == true) {
      return true;
    } else {
      return false;
    }
  }

  guardarSocioDesv(validar) {

    this.m_val = this.modalService.open(validar, { size: 'sm', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {

      this.m_val = this.modalService.open(validar, { size: 'sm' });
      this.load = true;
      this.buttonStatus = true;

      const formData = new FormData();
      formData.append('rut', this.user['rut']);
      formData.append('password', this.pass);
      formData.append('estado', 'aplicar_descuento_cc');

      this._validarusuario.validar_usuario(formData).subscribe((val) => {

        //si tiene acceso
        if (val > 0) {

          this.load = false;
          this.buttonStatus = false;
          this.pass = "";
          this.m_val.close();

          this.actualizarLoad2 = true;
          this._consorcioService.guardarPagoBeneficio(this.socio, this.tipoPorc, this.tipoRetiro, this.mesBeneficio, this.anio, this.montoFinal).subscribe((response) => {
            if (response.estado == 'failed') {
              alert(response.mensaje);
              this.actualizarLoad2 = false;
            }
            if (response.estado == 'success') {
              alert(response.mensaje);
              this.limpiarPagoBeneficio();
              this.mesBeneficio = '';
              this.actualizarLoad2 = false;
              this.modalPagoBeneficio.close();
              document.getElementById('refrescarTabla2').click();
            }
          },
            error => {
              console.log(error);
              this.blockIngreso = false;
              return false;
            }
          );

        } else {
          alert("Acceso denegado");
          this.load = false;
          this.buttonStatus = false;
          this.pass = "";
          this.m_val.close();
          return false;
        }

      }, response => { console.log("POST call in error", response); }, () => {
        console.log("The POST success.");
      });
      return false;


    }, (reason) => {
      console.log(`${reason}`);
    });

  }

  socioDesvinculado() {
    this._consorcioService.socios_sin_pb().subscribe((response) => {
      this.socioPB = response;
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );
  }

  descripcionDescuento() {
    this.blockLoad3 = false;
    this.ocultarDescripcion = true;
    this._consorcioService.descripcion_descuento(this.anio, this.mesBeneficio).subscribe((response) => {
      this.descripcionDesc = '';
      if (response.estado == "success") {
        this.blockLoad3 = true;
        this.ocultarDescripcion = false;
        this.descripcionDesc = response.mensaje;
      } else {
        this.blockLoad3 = true;
        this.ocultarDescripcion = false;
        this.descripcionDesc = "No Existe un pago de beneficio aun en el mes " + this.mesBeneficio;

      }

    },
      error => {
        console.log(error);
        return false;
      }
    );
  }

  acumuladoSiguienteAnio(validar) {
    this.m_val = this.modalService.open(validar, { size: 'sm', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {

      this.m_val = this.modalService.open(validar, { size: 'sm' });
      this.load = true;
      this.buttonStatus = true;

      const formData = new FormData();
      formData.append('rut', this.user['rut']);
      formData.append('password', this.pass);
      formData.append('estado', 'aplicar_descuento_cc');

      this._validarusuario.validar_usuario(formData).subscribe((val) => {

        //si tiene acceso
        if (val > 0) {

          this.load = false;
          this.buttonStatus = false;
          this.pass = "";
          this.m_val.close();

          this.actualizarLoad3 = true;
          this._consorcioService.acumulado_siguiente_anio(this.anio).subscribe((response) => {
            if (response.estado == 'failed') {
              alert(response.mensaje);
              this.actualizarLoad3 = false;
            }
            if (response.estado == 'success') {
              alert(response.mensaje);
              this.actualizarLoad3 = false;
              this.modalAcumulado.close();
              // document.getElementById('refrescarTabla2').click();
            }
          },
            error => {
              console.log(error);
            }
          );

        } else {
          alert("Acceso denegado");
          this.load = false;
          this.buttonStatus = false;
          this.pass = "";
          this.m_val.close();
          return false;
        }

      }, response => { console.log("POST call in error", response); }, () => {
        console.log("The POST success.");
      });
      return false;


    }, (reason) => {
      console.log(`${reason}`);
    });
  }

  ahorroDiaSueldo(){
    this._consorcioService.ahorro_dia_sueldo(this.anio).subscribe((response) => {
        this.totalAhorro = response;
    },
      error => {
        console.log(error);
      }
    );
  }

  limpiarPagoDiaSueldo() {
    this.fechaSocio = '';
    this.montoPrestamoSocio = '0';
    this.nDocumento = '';
    this.archivoDocumento = null;
  }

  pagoDiaSueldoCuentaSindical(id,descripcionSocio,montoDiaSueldo){
    this.actualizarLoad2 = true;
    this._consorcioService.insertar_dia_sueldo_socio(id,this.fechaSocio,descripcionSocio.value,montoDiaSueldo.value,this.montoPrestamoSocio,this.nDocumento,this.archivoDocumento).subscribe((response) => {
      console.log(response);
      if (response.estado == 'failed') {
        alert(response.mensaje);
        this.actualizarLoad2 = false;
      }
      if (response.estado == 'success') {
        alert(response.mensaje);
        this.limpiarPagoDiaSueldo();
        this.actualizarLoad2 = false;
        this.modalPago.close();
        // document.getElementById('refrescarTabla2').click();
      }
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );
  }

}
