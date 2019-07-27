import { Component, OnInit } from '@angular/core';
import { Meses } from 'src/app/modelos/meses.model';
import { Anios } from 'src/app/modelos/anios.model';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { HttpClient } from '@angular/common/http';
import { Prestamos } from 'src/app/modelos/prestamos.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-prestamos-socios',
  templateUrl: './tabla-prestamos-socios.component.html',
  styleUrls: ['./tabla-prestamos-socios.component.css']
})
export class TablaPrestamosSociosComponent implements OnInit {

  //objetos con valores de los select para ser manipulados
  selectAnio = {
    id: '1',
    descripcion: ''
  }

  selectMes = {
    id: '1',
    descripcion: ''
  }

  modalActualizarPagoSalud = null;

  //variable que almacena todos los prestamos que tiene el servidor
  valoresPrestamos;

  constructor(private _sindicalService: SindicalService,
     private _fechasService: AniosService,
     config: NgbModalConfig, 
     private modalService: NgbModal) {
      config.backdrop = 'static';
      config.keyboard = false;
      }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectAnio.id = '1';

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    this.selectMes.id = '1';

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        let AnioActual;
        AnioActual = response;
        this.selectAnio.id = AnioActual.id;
      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        let mesActual;
        mesActual = response;
        this.selectMes.id = mesActual.id;
        this.refrescarTablaPrestamosClientes();
      },
      error => {
        console.log(error);
      }
    )
  }

  openActualizar(Actualizar) {
    this.modalActualizarPagoSalud = this.modalService.open(Actualizar,{ size: 'sm' });    
   }
   cerrarActualizar(){
    this.modalActualizarPagoSalud.close();
  }

  refrescarTablaPrestamosClientes() {
    this._sindicalService.getPrestramosSocios(this.selectAnio.id, this.selectMes.id).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.valoresPrestamos = null;
          alert(response.mensaje);
        } else {
          this.valoresPrestamos = response;
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(evento) {
    this.selectAnio.id = evento.target.value;
    console.log(this.selectAnio.id);
    this.refrescarTablaPrestamosClientes();
  }

  changeMes(evento) {
    this.selectMes.id = evento.target.value;
    console.log(this.selectMes.id);
    this.refrescarTablaPrestamosClientes();
  }
}
