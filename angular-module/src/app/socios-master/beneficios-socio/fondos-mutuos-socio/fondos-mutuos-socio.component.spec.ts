import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FondosMutuosSocioComponent } from './fondos-mutuos-socio.component';

describe('FondosMutuosSocioComponent', () => {
  let component: FondosMutuosSocioComponent;
  let fixture: ComponentFixture<FondosMutuosSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FondosMutuosSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FondosMutuosSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
