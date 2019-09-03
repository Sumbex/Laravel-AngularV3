import { TestBed } from '@angular/core/testing';

import { BienestarService } from './bienestar.service';

describe('BienestarService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: BienestarService = TestBed.get(BienestarService);
    expect(service).toBeTruthy();
  });
});
