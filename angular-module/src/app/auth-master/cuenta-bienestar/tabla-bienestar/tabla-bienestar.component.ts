import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { BienestarService } from 'src/app/servicios/bienestar.service';

@Component({
  selector: 'app-tabla-bienestar',
  templateUrl: './tabla-bienestar.component.html',
  styleUrls: ['./tabla-bienestar.component.css']
})
export class TablaBienestarComponent implements OnInit {

  modalTablaBienestar = null;

  //variables select año y mes
  anio;
  anios;
  mes;
  meses;

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
    this.llenar_fecha();
  }

  openActualizar(Actualizar) {
    this.modalActualizar = this.modalService.open(Actualizar, { size: 'sm' });
    this.usuario_logeado();
  }

  cerrarActualizar() {
    this.modalActualizar.close();
    // this.actualizarMontoCajaChica = '';
  }

  llenar_fecha() {

    this._time.getAnios().subscribe(
      response => {
        this.anios = response;

        this._time.getAnioActual().subscribe(
          (response: { id }) => {
            this.anio = response.id;
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
