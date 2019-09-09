import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { BienestarService } from 'src/app/servicios/bienestar.service';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';

@Component({
  selector: 'app-tabla-bienestar',
  templateUrl: './tabla-bienestar.component.html',
  styleUrls: ['./tabla-bienestar.component.css']
})
export class TablaBienestarComponent implements OnInit {

  modalTablaBienestar = null;
  loading = false;

  //variables select año y mes

  anio; anios;
  mes; meses;
  idAnioActual;
  idMesActual;
  suc_res1 = false;
  suc_res2 = false;

  valorAnio: Anios = {
    descripcion: ''
  }
  valorMes: Meses = {
    descripcion: ''
  }

  //usuario logeado
  user: object = [];

  //Actualizar Campos en tabla
  entrada: any;
  archivoDocumento: any;
  m_val = null;
  load: boolean = false;
  buttonStatus: boolean = false;
  pass: string = '';
  actualizarLoad: boolean;
  modalActualizar = null;

  //tablas bienestar
  tablaBienestar;
  gas;
  reunion;
  votacion;
  fallecimiento;
  nacimiento;
  medico;
  cajaChica;
  resultado: any = ["total_final"];

  constructor(config: NgbModalConfig,
    private modalService: NgbModal,
    private _time: AniosService,
    private _validarusuario: ValidarUsuarioService,
    private _bienestarService: BienestarService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }



  openTablaBienestar(TablaBienestar) {
    this.modalTablaBienestar = this.modalService.open(TablaBienestar, { size: 'xl' });
    this.llenar_select();
  }

  openActualizar(Actualizar) {
    this.modalActualizar = this.modalService.open(Actualizar, { size: 'sm' });
    this.usuario_logeado();
  }

  cerrarActualizar() {
    this.modalActualizar.close();
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
      //  this.cierreMensualAnterior();
      this.refrescarBienestar();

    }
  }

  refrescarBienestar() {
    this.tablaBienestar = [];
    this.gas = [];
    this.reunion = [];
    this.votacion = [];
    this.cajaChica = [];
    this.fallecimiento = [];
    this.nacimiento = [];
    this.medico = [];
    this.resultado = [];
    this._bienestarService.getTablaBienestar(this.anio, this.mes).subscribe(
      response => {
        if (response == null) {
          this.tablaBienestar = [];
          this.gas = [];
          this.reunion = [];
          this.votacion = [];
          this.cajaChica = [];
          this.fallecimiento = [];
          this.nacimiento = [];
          this.medico = [];
          this.resultado = [];
        } else {
          this.tablaBienestar = response;
          this.gas = this.tablaBienestar.Cuenta_gas;
          this.reunion = this.tablaBienestar.inasistencia_reunion;
          this.votacion = this.tablaBienestar.inasistencia_votacion;
          this.cajaChica = this.tablaBienestar.caja_chica;
          this.fallecimiento = this.tablaBienestar.fallecimiento;
          this.nacimiento = this.tablaBienestar.nacimiento;
          this.medico = this.tablaBienestar.gastos_medicos;
          this.resultado = this.tablaBienestar.resultado;
        }
        this.loading = false;
      },
      error => {
        console.log(error);
      }
    );
    this.loading = true;
  }

  usuario_logeado() {

    this._validarusuario.usuario_logeado().subscribe((val: object) => {

      this.user = val;

    }, response => { console.log("POST call in error", response); }, () => {
      console.log("The POST success.");
    });
  }
  //actualizar items
  actualizar(id, campo, input, validar) {
    if (campo == "archivo") {
      if (campo == "undefined") {
        alert("ingrese documento porfavor!")
        return false;
      }
      this.entrada = this.archivoDocumento;
    } else {
      this.entrada = input.value;
    }
    if (this.entrada == '') {
      alert("ingrese datos porfavor!");
      return false;
    }
    this.m_val = this.modalService.open(validar, { size: 'sm', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {

      this.m_val = this.modalService.open(validar, { size: 'sm' });
      this.load = true;
      this.buttonStatus = true;

      const formData = new FormData();
      formData.append('rut', this.user['rut']);
      formData.append('password', this.pass);
      formData.append('estado', 'modificar_cb');

      this._validarusuario.validar_usuario(formData).subscribe((val) => {

        //si tiene acceso
        if (val > 0) {

          this.load = false;
          this.buttonStatus = false;
          this.pass = "";

          this.m_val.close();

          const form = new FormData();
          form.append('id', id);
          form.append('campo', campo);
          form.append('valor', input.value);

          this.actualizarLoad = true;
          this._bienestarService.getTablaBienestarActualizar(id, campo, this.entrada).subscribe(
            response => {
              if (response.estado == "success") {
                alert("" + response.mensaje + "");
                this.modalActualizar.close();
                //  this.refrescarSindical();
                this.actualizarLoad = false;
                this.pass = "";
              }
              if (response.estado == "failed") {
                alert("" + response.mensaje + "");
                this.actualizarLoad = false;
                this.pass = "";
                return false;
              }

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
      //this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });


  }

  onSelectImage(event) {
    this.archivoDocumento = event.srcElement.files[0];
  }

}
